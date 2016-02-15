var config = {
    paths: {
        'magebase/carousel': 'Magebase_Carousel/js/owl.carousel.min'
    },
    map:{
        '*':{
            carousel: 'Magebase_Carousel/js/owl.wrapper'
        }
    },
    shim: {
        'magebase/carousel':{
            deps: ['jquery']
        }
    }

}
