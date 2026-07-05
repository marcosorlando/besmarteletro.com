function initialize() {

    // Exibir mapa;
    var myLatlng = new google.maps.LatLng(-29.1722653, -51.2175537);
    var mapOptions = {
        zoom: 17,
        center: myLatlng,
        panControl: true,
        streetViewControlOptions: true,
        zoomControl: true,

        // mapTypeId: google.maps.MapTypeId.ROADMAP
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
        }
    }

    // Parâmetros do texto que será exibido no clique;
    var contentString = '<div style=\"width=200px; height: 180px; text-align: center;\"><h2><img src="https://suprimentosglobal.com.br/themes/global/images/logo/logo_dark.png" title="Localização da Global Suprimentos Industriais" alt="Global Suprimentos Industriais" target="_blank" width="200px"></h2>' +
        '<p style=\"text-align: center; font-weigth: 700;\"> Rua Valter Dalzoto, 95  <br>Bairro Cinquentenário - Caxias do Sul - RS</p>' +
        '<a style=\" text-decoration:none;\" href="https://www.google.com/maps/place/Global+Suprimentos+Industriais/@-29.1722653,-51.2175537,17z/data=!3m1!4b1!4m5!3m4!1s0x951ebd255b8e15ed:0x6ddb15a6cb579b16!8m2!3d-29.17227!4d-51.215365" target="_blank"><b>Como chegar?</b></a></div>';


    var infowindow = new google.maps.InfoWindow({
        content: contentString,

    });


    // Exibir o mapa na div #mapa;
    var map = new google.maps.Map(document.getElementById("map"), mapOptions);


    // Marcador personalizado;
    var image = '<?= INCLUDE_PATH ?>/images/icons/map_pin.png';
    console.log(image);
    var marcadorPersonalizado = new google.maps.Marker({
        position: myLatlng,
        map: map,
        icon: image,
        title: 'Global Suprimentos Industiais',
        animation: google.maps.Animation.DROP
    });


//   // Exibir texto ao clicar no ícone;
    google.maps.event.addListener(marcadorPersonalizado, 'click', function () {
        infowindow.open(map, marcadorPersonalizado);
    });


    // Estilizando o mapa;
    // Criando um array com os estilos
    var styles = [
        {
            stylers: [
                {hue: '#40C175'},
                {saturation: 60},
                {lightness: 35},
                {gamma: 0.2}
            ]
        },
        {
            featureType: "road",
            elementType: "geometry",
            stylers: [
                {lightness: 100},
                {visibility: "simplified"}
            ]
        },
        {
            featureType: "road",
            elementType: "labels"
        }
    ];

    // crio um objeto passando o array de estilos (styles) e definindo um nome para ele;
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Global Suprimentos"
    });

    // Aplicando as configurações do mapa
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');

}


// Função para carregamento assíncrono
function loadScript() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyD4y520O8n6DASxi4U1bdhgOxfJzTL1fDE&sensor=true&callback=initialize";
    document.body.appendChild(script);
}

window.onload = loadScript;
