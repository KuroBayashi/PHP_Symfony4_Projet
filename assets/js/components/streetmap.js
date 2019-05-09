class StreetMap {

    static TYPES = {
        DEFAULT: 'default',
        DEFIBRILLATOR_EDIT: 'defibrillator_edit',
        DEFIBRILLATOR_REPORT: 'defibrillator_report',
        MAINTENANCE_SHOW: 'maintenance_show',
    };

    /**
     * Constructor
     */
    constructor() {
        // Define user
        this.user = L.latLng([0, 0]);
        // Define map
        this.map = undefined;
        // Define types
        this.type = undefined;
        // Define icons
        let IconsConfig = L.Icon.extend({
            options: {
                iconSize: [25, 41],
                iconAnchor: [12.5, 41],
                popupAnchor: [0, -40]
            }
        });
        this.icons = {
            'blue':   new IconsConfig({iconUrl: '/build/images/map/marker-icon-blue.png'}),
            'green':  new IconsConfig({iconUrl: '/build/images/map/marker-icon-green.png'}),
            'orange': new IconsConfig({iconUrl: '/build/images/map/marker-icon-orange.png'}),
            'red':    new IconsConfig({iconUrl: '/build/images/map/marker-icon-red.png'})
        };
        // Define markers
        this.markers = [];
    }

    /**
     * Init
     */
    init(mapId, type) {
        this.type = type || StreetMap.TYPES.DEFAULT;
        this.map = L.map(mapId).locate({setView: true});

        if (type === StreetMap.TYPES.DEFIBRILLATOR_EDIT) {
            this.onClickCreateDefibrillator();
        }

        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            minZoom: 2,
            maxZoom: 18,
            id: 'mapbox.streets',
            accessToken: 'pk.eyJ1Ijoia3Vyb2JheWFzaGkiLCJhIjoiY2p0b290czFrMDFxYTN5cGU2MHJuOGduMiJ9.WMRUB1J71fY_gbOjUiL6MA'
        }).addTo(this.map);
    }

    /**
     * getMarker : Get custom marker (color set by distance)
     */
    getMarker(originLat, originLng, targetLat = null, targetLng = null, options = {}) {
        targetLat = targetLat || originLat;
        targetLng = targetLng || originLng;

        let dist = StreetMap.distance(originLat, originLng, targetLat, targetLng);

        options.icon =
            (dist === 0) ? this.icons.blue :    // User
            (dist < 250) ? this.icons.green :
            (dist < 500) ? this.icons.orange :
            this.icons.red
        ;

        return L.marker(L.latLng([targetLat, targetLng]), options);
    }

    /**
     * loadMarkers
     */
    loadMarkers() {
        let bounds = this.map.getBounds();

        $.getJSON("/api/defibrillator/visible/"+ bounds.getWest() +"/"+ bounds.getEast() +"/"+ bounds.getSouth() +"/"+ bounds.getNorth(), (data) => {
            $.each(data, (i, defibrillator) => {
                let marker = this.getMarker(this.user.lat, this.user.lng, defibrillator.latitude, defibrillator.longitude, {id: defibrillator.id});

                if (this.type === StreetMap.TYPES.DEFIBRILLATOR_EDIT) {
                    this.onClickEditDefibrillator(marker);
                }
                else if (this.type === StreetMap.TYPES.DEFIBRILLATOR_REPORT) {
                    this.onClickReportDefibrillator(marker);
                }

                // Check if the marker has already been added
                if (-1 === this.markers.findIndex(m => m.options.id === marker.options.id)) {
                    this.map.addLayer(marker);
                    this.markers.push(marker);
                }
            });
        });
    };

    /**
     * geolocalise
     */
    geolocalise() {
        this.map.on('locationfound', (e) => {
            this.user = e.latlng;

            this.map.setZoom(16);

            let marker = this.getMarker(this.user.lat, this.user.lng);
            marker.addTo(this.map)
                .bindPopup("You are within " + (e.accuracy / 2) + " meters from this point \n ("+ this.user.lat + ", " + this.user.lng + ")")
                .openPopup()
            ;

            L.circle(this.user, (e.accuracy / 2)).addTo(this.map);

            this.onMapUpdate();
        });
        this.map.on('locationerror', (e) => {
            alert(e.message);
        });
    }

    onMapUpdate() {
        this.map.on('zoomend', () => {
            this.loadMarkers();
        });

        this.map.on('dragend', () => {
            this.loadMarkers();
        });
    }

    onClickCreateDefibrillator() {
        this.map.on('click', (e) => {
            $.getJSON("/defibrillator/new", (data) => {
                $('#modal').remove();
                $('main').append(data.form);
                $('#modal').modal('show');

                $("[name='defibrillator[latitude]']").val(parseFloat(e.latlng.lat));
                $("[name='defibrillator[longitude]']").val(parseFloat(e.latlng.lng));
            }).then(() => {
                this.handleDefibrillatorFormSubmit();
            });
        });
    }

    onClickEditDefibrillator(marker) {
        marker.on('click', (e) => {
            $.getJSON("/defibrillator/" + e.target.options.id + "/edit", (data) => {
                $('#modal').remove();
                $('main').append(data.form);
                $('#modal').modal('show');
            }).then(() => {
                this.handleDefibrillatorFormSubmit();
                this.handleDefibrillatorFormDelete();
            });
        });
    }

    onClickReportDefibrillator(marker) {
        marker.on('click', (e) => {
            $.getJSON("/defibrillator/" + e.target.options.id + "/report", (data) => {
                $('#modal').remove();
                $('main').append(data.form);
                $('#modal').modal('show');
            }).then(() => {
                this.handleDefibrillatorFormReport();
            });
        });
    }

    handleDefibrillatorFormSubmit() {
        $(".js-modal-submit").on('click', (e) => {
            e.preventDefault();

            let form = $('#modal-form');

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                beforeSend: () => {
                    $('.modal-footer .btn').attr("disabled","disabled");
                    $('.modal-body').css('opacity', '.5');
                },
                success: (data) => {
                    if (data.success) {
                        $('#modal').modal('hide');

                        // Delete old marker
                        for (let i = 0; i < this.markers.length; ++i) {
                            if (this.markers[i].options.id === data.defibrillator.id) {
                                this.map.removeLayer(this.markers[i]);
                            }
                        }
                        // Add new marker
                        let marker = this.getMarker(this.user.lat, this.user.lng, data.defibrillator.latitude, data.defibrillator.longitude, {id: data.defibrillator.id});
                        this.onClickEditDefibrillator(marker);

                        this.map.addLayer(marker);
                        this.markers.push(marker);
                    }
                    else {
                        $('.modal-footer .btn').removeAttr("disabled");
                        $('.modal-body').css('opacity', '1');

                        $('#js-modal .modal-body').html(data.form);
                        $('#js-modal').modal('handleUpdate');
                    }
                }
            });
        })
    }

    handleDefibrillatorFormDelete() {
        $(".js-modal-delete").on('click', (e) => {
            e.preventDefault();

            let form = $('#modal-form-delete');

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                beforeSend: () => {
                    $('.modal-footer .btn').attr("disabled","disabled");
                    $('.modal-body').css('opacity', '.5');
                },
                success: (data) => {
                    $('#modal').modal('hide');

                    // Delete marker
                    for (let i = 0; i < this.markers.length; ++i) {
                        if (this.markers[i].options.id === data.id) {
                            this.map.removeLayer(this.markers[i]);
                        }
                    }
                }
            });
        })
    }

    handleDefibrillatorFormReport() {
        $(".js-modal-report").on('click', (e) => {
            e.preventDefault();

            let form = $('#modal-form');

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                beforeSend: () => {
                    $('.modal-footer .btn').attr("disabled","disabled");
                    $('.modal-body').css('opacity', '.5');
                },
                success: (data) => {
                    $('#modal').modal('hide');

                    // Reported popup
                    for (let i = 0; i < this.markers.length; ++i) {
                        if (this.markers[i].options.id === data.defibrillator.id) {
                            this.markers[i].bindPopup("Successfully reported!").openPopup();
                        }
                    }
                }
            });
        })
    }

    goTo(lat, lng) {
        this.map.setView(new L.LatLng(lat, lng), 18);
        this.map.setZoom(17);
    }


    /**
     * Utilities
     */
    // Distance (in meters) between 2 points using lat / long coordinates
    static distance(lat1, lon1, lat2, lon2) {
        let R = 6378.137; // Radius of earth in KM

        let dLat = lat2 * Math.PI / 180 - lat1 * Math.PI / 180;
        let dLon = lon2 * Math.PI / 180 - lon1 * Math.PI / 180;

        let a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon/2) * Math.sin(dLon/2)
        ;
        let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        let d = R * c;

        return d * 1000;
    };
}

module.exports = StreetMap;