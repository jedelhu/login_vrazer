$(function () {
    $('.selection-folders').hide();
    $('.return-btn').hide();

    $('.return-btn').click(function (e) {
        $(".steps-numeric-header-in ul li").eq(1).removeClass("active");
        $(".steps-numeric-header-in ul li").eq(0).addClass("active");
        $('.connection-detail').show();
        $('#btn-connection').show();
        $('#btn-connection2').show();
        $('.selection-folders').hide();
        $('.return-btn').hide();
    });
    $('.swal-btn-success').click(function (e) {
        e.preventDefault();
        swal({
            title: "Connecting ...",
            text: "Please wait",
            type: "success",
            showCancelButton: true,
        });

        $('.cancel').click(function (e) {
            var url = window.location.href;
            window.location.replace(url);
        });
    });
    //var current_url = window.location.href;
    //if (current_url == 'http://loginportal.vrazer.net/admin/mysqlconnection') {
    var name = $("#form-connection2 input[type='radio']:checked").val();
    if (name == 'ssh') {
        $('.ssh-part').show();
    } else {
        $('.ssh-part').hide();
    }
    $('#form-connection2 input[type=radio][name=type]').change(function () {
        var name = $("#form-connection2 input[type='radio']:checked").val();
        if (name == 'ssh') {
            $('.ssh-part').show();
        } else {
            $('.ssh-part').hide();
        }
    });
    //}

    $("#btn-connection").click(function () {
        $('#dir').val('');
        var formData = $("#form-connection").serialize();
        //var formURL = $(this).attr("action");
        var formURL = 'http://loginportal.vrazer.net/admin/connection';
        $('#errors').html('');
        localStorage.clear();

        $.ajax(
            {
                type: "POST",
                cache: false,
                data: formData,
                url: formURL,
                dataType: "json",
                success: function (data) {
                    $(this).removeAttr('disabled');
                    swal.close();
                    $(".steps-numeric-header-in ul li").eq(0).removeClass("active");
                    $(".steps-numeric-header-in ul li").eq(1).addClass("active");
                    $('.connection-detail').hide();
                    $('#btn-connection').hide();
                    $('.selection-folders').show();
                    $('.return-btn').show();

                    if (data.status == 'error') {
                        errorsHtml = '<ul class="alert alert-danger">';
                        errorsHtml += '<li>' + data.message + '</li>';
                        errorsHtml += '</ul>';
                        $('#errors').html(errorsHtml);
                    }
                    if (data.status == 'success') {
                        var obj = jQuery.parseJSON(data.list);
                        var treeHtml = '<ul class="jstree-container-ul">';
                        var i = 1;
                        $.each(obj, function (index, element) {

                            if ((element.filename != '.' && element.filename != '..') || (element != '.' && element != '..' && data.type == 'ftp')) {
                                var idd = 'j1_' + i;
                                if (element.type == 2) {
                                    treeHtml += '<li role="treeitem" id="j1_' + i + '" class="jstree-node  jstree-leaf" aria-selected="false" aria-expanded="false">';
                                }
                                else {
                                    treeHtml += '<li role="treeitem" id="j1_' + i + '" class="jstree-node  jstree-leaf" aria-selected="false" aria-expanded="false" style="margin-left: 27px;">';
                                }

                                if (data.type == 'ftp') {

                                    if (element.type == 2) {

                                        treeHtml += '<a class="folder jstree-anchor"  data-id="' + element.filename + '" href="javascript:void(0);"><i style=" margin-right: 10px;" class="fa fa-caret-right" aria-hidden="true"></i></a><input  type="checkbox" name="list[]" value="' + element.filename + '" class="liChild"><a class="folder jstree-anchor"  data-id="' + element.filename + '" href="javascript:void(0);"><i class="jstree-icon jstree-themeicon fa fa-folder jstree-themeicon-custom"></i> ' + element.filename + '</a>';

                                    }
                                    else {
                                        treeHtml += '<input type="checkbox" name="list[]" value="' + element.filename + '"><i class="jstree-icon jstree-themeicon fa fa-file-o jstree-themeicon-custom filee"></i> ' + element.filename;
                                    }

                                }
                                else {
                                    if (element.type == 2) {
                                        treeHtml += '<a class="folder jstree-anchor" data-id="' + element.filename + '" href="javascript:void(0);"><i style=" margin-right: 10px;" class="fa fa-caret-right" aria-hidden="true"></i></a><input  type="checkbox"  name="list[]" value="' + element.filename + '" class="liChild"><a class="folder jstree-anchor atag" data-id="' + element.filename + '" href="javascript:void(0);"><i class="jstree-icon jstree-themeicon fa fa-folder jstree-themeicon-custom "></i> ' + element.filename + '</a>';
                                    }
                                    else {
                                        treeHtml += '<input type="checkbox" name="list[]" value="' + element.filename + '"><i class="jstree-icon jstree-themeicon fa fa-file-o jstree-themeicon-custom filee"></i> ' + element.filename;
                                    }
                                }
                                treeHtml += '</li>';
                                i++;
                            }
                        });
                        treeHtml += '</ul>';
                        $('#tree1').html(treeHtml);
                        $("a.next-btn").trigger("click");


                    }
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    $(this).removeAttr('disabled');
                    swal.close();

                    if (jqXhr.status === 401) { //redirect if not authenticated user.
                        $('#errors').html('<ul class="alert alert-danger"><li>' + jqXhr.status + ': ' + errorThrown + '</li></ul>');
                    }

                    if (jqXhr.status === 422) {
                        var errorsHtml = '<ul class="alert alert-danger">';
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul>';
                        $('#errors').html(errorsHtml);

                    }
                    else {
                        $('#errors').html('<ul class="alert alert-danger"><li>' + jqXhr.status + ': ' + errorThrown + '</li></ul>');
                    }
                }
            });


    });
    $(document).on("click", "a.folder", function () {

        var object = $(this);
        var href = object.attr('href');
        var dir = object.data("id");
        var liid = object.closest("li").attr('id');
        var atag = $('#' + liid).find('a:eq(1)');

        var spinner = $("<img src='http://loginportal.vrazer.net/images/ajax-loader.gif' />").insertAfter(atag);
        var id = object.closest('li').attr('id');
        var check = "";
        var storedfolder = [];
        if (localStorage.getItem("storedfolder") === null) {

        }
        else {
            storedfolder = localStorage.getItem('storedfolder');
            storedfolder = JSON.parse(storedfolder);
        }

        if (storedfolder.length > 0) {


            for (i = 0; i < storedfolder.length; i++) {

                if (storedfolder[i] == id) {
                    check = false;

                    $("#" + id).find("a i").first().remove();
                    $("#" + id).find('a').first().prepend('<i  style=" margin-right: 10px;" class="fa fa-caret-right" aria-hidden="true"></i>');
                    break;
                }
                else {
                    check = true;
                }
            }
            if (check) {
                storedfolder.push(id);
                localStorage.setItem('storedfolder', JSON.stringify(storedfolder));
                var check = true;

                $("#" + id).find("a i").first().remove();
                $("#" + id).find('a').first().prepend('<i  style=" margin-right: 10px;" class="fa fa-caret-down" aria-hidden="true"></i>');


            }
        }
        else {
            storedfolder.push(id);
            localStorage.setItem('storedfolder', JSON.stringify(storedfolder));
            check = true;


            $("#" + id).find("a i").first().remove();
            $("#" + id).find('a').first().prepend('<i style=" margin-right: 10px;" class="fa fa-caret-down" aria-hidden="true"></i>');
        }

        dir = dir.replace('#', '');
        $('#dir').val(dir);

        var formData = $("#form-connection").serialize();

        var formURL = 'http://loginportal.vrazer.net/admin/connection';

        if (check) {
            $.ajax(
                {
                    type: "POST",
                    cache: false,
                    data: formData,
                    url: formURL,
                    dataType: "json",
                    success: function (data) {

                        spinner.remove();
                        if (data.status == 'error') {
                            errorsHtml = '<ul class="alert alert-danger">';
                            errorsHtml += '<li>' + data.message + '</li>';
                            errorsHtml += '</ul>';
                            $('#errors').html(errorsHtml);
                        }
                        if (data.status == 'success') {
                            var obj = jQuery.parseJSON(data.list);
                            var ctreeHtml = ' <ul style="display:block;" role="group" class="jstree-children">';
                            $.each(obj, function (index, element) {
                                if ((element.filename != '.' && element.filename != '..') || (element != '.' && element != '..' && data.type == 'ftp')) {

                                    if (element.type == 2) {
                                        ctreeHtml += '<li role="treeitem" id="j1_1' + index + '" class="jstree-node  jstree-leaf" aria-selected="false">';
                                    }
                                    else {
                                        ctreeHtml += '<li role="treeitem" id="j1_1' + index + '" class="jstree-node  jstree-leaf" aria-selected="false" style="margin-left: 51px">';

                                    }
                                    if (data.type == 'ftp') {

                                        if (element.type == 2) {

                                            ctreeHtml += '<a class="folder jstree-anchor" data-id="#' + dir + '/' + element.filename + '" href="javascript:void(0);" ><i style=" margin-right: 10px;" class="fa fa-caret-right" aria-hidden="true"></i></a><input type="checkbox" name="list[]" value="' + dir + '/' + element.filename + '" class="liChild"><a class="folder jstree-anchor atag" data-id="#' + dir + '/' + element.filename + '" href="javascript:void(0);" ><i class="jstree-icon jstree-themeicon fa fa-folder jstree-themeicon-custom"></i> ' + element.filename + '</a>';
                                        }
                                        else {
                                            ctreeHtml += '<input type="checkbox" name="list[]" value="' + dir + '/' + element.filename + '"><i class="jstree-icon jstree-themeicon fa fa-file-o jstree-themeicon-custom filee"></i> ' + element.filename;
                                        }
                                    }
                                    else {
                                        if (element.type == 2) {

                                            ctreeHtml += '<a class="folder jstree-anchor" data-id="#' + dir + '/' + element.filename + '" href="javascript:void(0);" ><i style=" margin-right: 10px;" class="fa fa-caret-right" aria-hidden="true"></i></a><input type="checkbox" name="list[]" value="' + dir + '/' + element.filename + '" class="liChild"><a class="folder jstree-anchor atag" data-id="#' + dir + '/' + element.filename + '" href="javascript:void(0);" ><i class="jstree-icon jstree-themeicon fa fa-folder jstree-themeicon-custom"></i> ' + element.filename + '</a>';
                                        }
                                        else {
                                            ctreeHtml += '<input type="checkbox" name="list[]" value="' + dir + '/' + element.filename + '"><i class="jstree-icon jstree-themeicon fa fa-file-o jstree-themeicon-custom filee"></i> ' + element.filename;

                                        }
                                    }
                                    ctreeHtml += '</li>';
                                }


                            });
                            ctreeHtml += '</ul>';
                            object.parent().find('ul.jstree-children').remove();
                            object.parent().append(ctreeHtml);
                            $('input.liChild').unbind('change');
                            $('input.liChild').on('change', function () {

                                stored = localStorage.getItem('storedfolder');

                                var id = $(this).closest('li').attr('id');
                                var val = $(this).is(':checked');

                                if (stored != null) {
                                    $('#' + id + ' ul').find(':checkbox').each(function () {
                                        if ($(this).is(':checked') && val) {
                                            $(this).prop('checked', true);
                                        } else if (!$(this).is(':checked') && !val) {
                                            $(this).prop('checked', false);
                                        } else if ($(this).is(':checked')) {
                                            $(this).prop('checked', false);
                                        }
                                        else {

                                            $(this).prop('checked', true);
                                        }
                                    });

                                } else {

                                }
                                //console.log(index);
                            });


                        }

                    },
                    error: function (jqXhr, json, errorThrown) {
                        var errors = jqXhr.responseJSON;

                        if (jqXhr.status === 401) { //redirect if not authenticated user.
                            alert('Error ' + jqXhr.status + ': ' + errorThrown);
                        }

                        if (jqXhr.status === 422) {
                            var errorsHtml = '<ul class="alert alert-danger">';
                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>';
                            });
                            errorsHtml += '</ul>';
                            $('#errors').html(errorsHtml);

                        }
                        else {
                            alert('Error ' + jqXhr.status + ': ' + errorThrown);
                        }
                    }
                });
        }
        else {
            spinner.remove();
            $('#' + id + ' ul').hide();
            var index = storedfolder.indexOf(id);
            if (index > -1) {
                storedfolder.splice(index, 1);
                localStorage.setItem('storedfolder', JSON.stringify(storedfolder));
            }
        }
    });

    $("#btn-connection2").click(function () {
        var formData = $("#form-connection2").serialize();

        var formURL = 'http://loginportal.vrazer.net/admin/mysqlconnection';

        $.ajax(
            {
                type: "POST",
                cache: false,
                data: formData,
                url: formURL,
                dataType: "json",
                success: function (data) {
                    swal.close();
                    if (data.status == 'error') {
                        errorsHtml = '<ul class="alert alert-danger">';
                        errorsHtml += '<li>' + data.message + '</li>';
                        errorsHtml += '</ul>';
                        $('#errors').html(errorsHtml);
                    }
                    if (data.status == 'success') {

                        $(".steps-numeric-header-in ul li").eq(0).removeClass("active");
                        $(".steps-numeric-header-in ul li").eq(1).addClass("active");
                        $('.connection-detail').hide();
                        $('#btn-connection2').hide();
                        $('.selection-folders').show();
                        $('.return-btn').show();
                        $('#tree1 ul').remove();
                        var ctreeHtml = ' <ul style="display:block;margin-bottom: 20px;" role="group" class="jstree-children">';

                        $.each(data.list, function (i, element) {

                            ctreeHtml += '<li role="treeitem" id="mysql_j" class="jstree-node  jstree-leaf" aria-selected="false" style="margin-left: 0px">';
                            ctreeHtml += '<input style="margin-right: 10px;" type="checkbox" name="list[]">' + element;
                            ctreeHtml += '</li>';

                        });
                        ctreeHtml += '</ul>';
                        $('#tree1').append(ctreeHtml);
                        $('#errors ul').remove();
                    }
                },
                error: function (jqXhr, json, errorThrown) {
                    swal.close();
                    var errors = jqXhr.responseJSON;

                    if (jqXhr.status === 401) { //redirect if not authenticated user.
                        alert('Error ' + jqXhr.status + ': ' + errorThrown);
                    }

                    if (jqXhr.status === 422) {
                        var errorsHtml = '<ul class="alert alert-danger">';
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul>';
                        $('#errors').html(errorsHtml);

                    }
                    else {
                        alert('Error ' + jqXhr.status + ': ' + errorThrown);
                    }
                }
            });
    });


});

