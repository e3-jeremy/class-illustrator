<style>
    #main.card.border.border-primary {border: 1px solid rgba(0,0,0,.125) !important; }
    #header.card-header.bg-info.bg-gradient {background-color: rgba(0,0,0,.03) !important; }
    .card-header {cursor: pointer; }
    .default-font-size {font-size: 13px; }
    .smaller-font-size {font-size: 11px; }
    .method-info,
    .fake-footer {
        background-color: #fff;
        border-radius: 4px;
        border: 2px solid #0086b3;
        position: absolute;
        right: calc(-100% - 25px);
        top: -18px;
        width: calc(100%);
        z-index: 999;
        transition: top 0.7s ease, bottom 0.7s ease;
    }
    .arrow-left-coloured,
    .arrow-left-white {
        border-bottom: 13px solid transparent;
        border-top: 13px solid transparent;
        height: 0;
        position: absolute;
        top: 6px;
        width: 0;
    }
    .arrow-left-coloured {
        border-right: 13px solid #0086b3;
        right: -25px;
    }
    .arrow-left-white {
        border-right: 13px solid #fff;
        right: -28px;
        z-index: 99999;
    }
    .fake-footer {
        height: 55px;
        top: -15px;
    }
    .card-header .fake-footer,
    .card-header .arrow-left-white,
    .card-header .arrow-left-coloured {
        display: none;
    }
    .card-header.info-visible .fake-footer,
    .card-header.info-visible .arrow-left-white,
    .card-header.info-visible .arrow-left-coloured {
        display: block !important;
    }
    .print_data {
        min-height: calc(100% - 70px);
        position: relative;
    }
    .print_data .notify {
        color: #837b7b;
        font-size: 18px;
        font-weight: bold;
        left: 50%;
        position: absolute;
        text-shadow: 1px 2px 1px #fff;
        top: 170px;
        transform: translate(-50%);
        text-align: center;
    }
    code {

    }

</style>