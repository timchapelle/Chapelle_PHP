$(function () {

    // Initialisation des tooltip Twitter Bootstrap
    $('[data-toggle="tooltip"]').tooltip();

    // Toggle du bouton menu
    $(".menu-toggle-btn").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

    // Initialisation de l'arbre des dossiers
    $('#file_tree').fileTree({
        root: '/Chapelle_PHP/',
        script: '../bower_components/jqueryfiletree/dist/connectors/jqueryFileTree.php',
        expandSpeed: 200,
        collapseSpeed: 200,
        multiFolder: false
    }, function (file) {

        // 'file' est un string contenant le chemin absolu vers le fichier,
        // je vais donc lui retirer 'Chapelle_PHP' pour améliorer la lisibilité.
        var regExp = /Chapelle_PHP\//g;
        var result = file.replace(regExp, "");

        alert('Veuillez vous rendre dans la documentation ou sur GitHub (voir footer) pour avoir accès au code de \n ' + result);
        // Mise en évidence du bouton de documentation
        $('#docLink').css({
            'color': 'red',
            'font-size': '20px',
            'transition': '0.3s'
        }).addClass('faa-horizontal animated');

        $('#github-icon').addClass('faa-shake animated');
        $('#github-icon').attr('style', 'color:red');
    });

    $('#github-icon').hover(function () {
        $(this).removeClass('faa-shake animated');
        $(this).attr('style', '')
    })

    // Animation sur le bouton documentation
    $('#docLink').on({
        'mouseover': function () {
            $(this).removeClass('faa-horizontal animated');
        },
        'click': function () {
            $(this).css({
                'color': '#999',
                'font-size': 'inherit',
                'transition': '0s'
            });
        }
    });

    // Animation de la bannière de la homepage
    $('#homeBanner').on({
        'mouseenter': function () {
            $('#wrenchIcon, #carIcon').addClass('animated');
        },
        'mouseleave': function () {
            $('#wrenchIcon, #carIcon').removeClass('animated');
        }
    });

    // Affichage/masquage de la description des réparations (fiche véhicule)
    $('.show-reparation-desc').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var id = $(this).attr('id').split('-')[2];
        $('#description-' + id).toggleClass('hide');
        $('#icon-' + id).toggleClass('fa-eye fa-eye-slash');
        var title = $(this).attr('data-original-title');
        var newTitle = (title === "Afficher la description") ? "Masquer la description" : "Afficher la description";
        $(this).attr('data-original-title', newTitle);
    });

    // Affichage/masquage des infos (fiche véhicule)
    var toggleInfo = true;
    $('.show-infos').click(function () {

        if (toggleInfo) {
            $('#infos-ul').slideToggle('fast',
                    function () {
                        $('#infos').fadeOut(10);
                    });
        } else {
            $('#infos').fadeIn(10,
                    function () {
                        $('#infos-ul').slideToggle('slow');
                    });
        }
        toggleInfo = !toggleInfo;
        $(this).find('i').toggleClass('fa-eye-slash fa-eye');
    });

    // Affichage/masquage des infos (fiche véhicule)
    var toggleRep = true;
    $('.show-reparations').click(function () {
        // Vérification de l'élément à 'slider' : soit l'ul des 
        // réparations, soit l'alerte comme quoi il n'y en a pas

        var rep = $('#reparations-ul')[0];

        if (rep) {
            if (toggleRep) {
                $('#reparations-ul').slideToggle('slow',
                        function () {
                            $('#reparations').fadeOut(10);
                        });
            } else {
                $('#reparations').fadeIn(10,
                        function () {
                            $('#reparations-ul').slideToggle('slow');
                        });
            }
        } else {
            if (toggleRep) {
                $('#alert').slideToggle('slow',
                        function () {
                            $('#reparations').fadeOut(10);
                        });
            } else {
                $('#reparations').fadeIn(10,
                        function () {
                            $('#alert').slideToggle('slow');
                        });
            }
        }
        // Inversion
        toggleRep = !toggleRep;
        // Inversion du sens de l'icône (bas <-> haut)
        $(this).find('i').toggleClass('fa-eye-slash fa-eye');
    });

    /**
     * Initialisation du datepicker (pour entrer facilement les dates sur Firefox)
     */
    var date = $('#date').val();
    $('.datepicker').datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: date
    });

    $('.datepicker').datepicker("option", $.datepicker.regional["fr"]);
});

$('#show-list').click(function (e) {
    e.preventDefault();
    var vp = $('#vehicules-panels');
    if (!vp.hasClass('hide')) {
        vp.fadeOut();
    }
    $(this).removeClass('btn-default').addClass('btn-primary');
    $('#show-panels').removeClass('btn-primary').addClass('btn-default');
    $('#vehicules-list').fadeIn();
});

$('#show-panels').click(function (e) {
    e.preventDefault();
    var vl = $('#vehicules-list');
    vl.fadeOut();
    $('#show-list').removeClass('btn-primary').addClass('btn-default');
    $(this).removeClass('btn-default').addClass('btn-primary');
    $('#vehicules-panels').fadeIn();
});


$('#csv-btn').click(function (e) {
    e.preventDefault();
    $('#alert-csv').toggleClass('hide');

});

$('#csv-btn-2').click(function () {
    $('#csv-input').click();
});

var files;

$('#csv-input').change(function (e) {
    files = e.target.files;
    var file = e.target.files[0];
    console.log(file);
    var response = file.name + " (" + file.size / 1000 + "kb) : " + (file.type === "text/csv" ? "Fichier valide" : "Veuillez choisir un fichier .csv");
    $('#response').fadeIn();
    $('#response-txt').text(response);

    if (file.type !== "text/csv") {
        $('#submit').prop('disabled', 'true');
        $('#response').removeClass('alert-info').removeClass('alert-success').addClass('alert-danger');
    } else {
        $('#response').removeClass('alert-danger').removeClass('alert-info').addClass('alert-success');
        $('#submit').prop('disabled', '').removeClass('btn-success').addClass('btn-default');
    }
});
$('#csv-form').submit(function (e) {

    e.stopPropagation();
    e.preventDefault();

    var fd = new FormData();

    $.each(files, function (key, value) {
        fd.append(key, value);
    });
    console.log('fd');
    console.log(fd);
    $.post({
        url: 'index.php?p=vehicules.importCSV',
        data: fd,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (data)
        {
            console.log('réussite de la requete ajax');
            console.log(data);
            var totalErr = data.errorRows != "" ? parseInt(data.errorRows, 10) : 0;
            var totalIns = data.insertedRows != "" ? parseInt(data.insertedRows, 10) - 1 : 0;
            var total = totalErr + totalIns;
            var rowNum = data.errorRowNumbers != "" ? " lignes (" + data.errorRowNumbers + ")" : "";
            var responseTxt = "Total des lignes : " + total
                    + "<br> Lignes insérées : " + totalIns
                    + "<br> Erreurs : " + totalErr + rowNum;
            $('#response-txt').html(responseTxt);
            var link = $('<a></a>');
            link.attr('href', 'index.php?p=vehicules.indexPHP')
                    .attr('class', 'btn btn-sm btn-primary')
                    .append('<i class="fa fa-refresh fa-fw"></i> Rafraichir');
            $('#submit-csv').hide().after(link);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            // Handle errors here
            console.log(jqXHR, textStatus, errorThrown);
            // STOP LOADING SPINNER
        }
    });

});

