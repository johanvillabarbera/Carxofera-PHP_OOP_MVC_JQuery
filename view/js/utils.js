//================AJAX PROMISE================
function ajaxPromise(sUrl, sType, sTData, sData = undefined) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: sUrl,
            type: sType,
            dataType: sTData,
            data: sData
        }).done((data) => {
            resolve(data);
        }).fail((jqXHR, textStatus, errorThrow) => {
            reject(errorThrow);
        }); 
    });
}


//================LOAD-HEADER================
function load_menu() {
    var token = localStorage.getItem('token');
    if (token) {
        ajaxPromise('module/login/controller/controller_login.php?op=data_user', 'POST', 'JSON', { 'token': token })
            .then(function(data) {
                $('.profile-dropdown').hide();
                $('.login-icon').hide();
                $('<div></div>').attr({ 'class': 'profile-dropdown' }).attr({ 'style': 'margin-top: 15px;' }).appendTo('.navbar-nav')
                    .html(
                        '<a href="#" class="display-picture"><img src="'+ data.avatar +'" alt=""></a>' +
                        '<div class="profile-dropdown">' + 
                        '<div class="card-profile" style="display: none;">' +
                        '<ul>' +
                        '<li><a style="color: black;">'+ data.username +'</li></a>' +
                        '<li><a href="#" class="logout-icon" style="color:#fff">Logout</li></a>' +
                        '</ul>' +
                        '</div>' +
                        '</div>'
                    );

                $(document).mouseup(function(e) {
                    $('.display-picture').on('click', function(e) {
                        $('.card-profile').toggle();
                    });

                    var container = $('.card-profile');

                    if (!container.is(e.target) && container.has(e.target).length === 0) 
                    {
                        container.hide();
                    }
                });

                $('<a class="cart-icon" href="index.php?page=controller_cart&op=list"><i class="fa-solid fa-cart-shopping"></i></a>').appendTo('.cart');
            }).catch(function() {
                console.log("Error al cargar los datos del user");
            });
    } else {
        $('.profile-dropdown').hide();
        $('.login-icon').show();
    }
}

//================CLICK-LOGOUT================
function click_logout() {
    $(document).on('click', '.logout-icon', function() {
        toastr.success("Logout succesfully");
        setTimeout('logout(); ', 1000);
    });
}

//================LOG-OUT================
function logout() {
    ajaxPromise('module/login/controller/controller_login.php?op=logout', 'POST', 'JSON')
        .then(function(data) {
            console.log(data);
            localStorage.removeItem('token');
            localStorage.removeItem('token_refresh');
            window.location.href = "index.php?page=controller_home&op=list";
        }).catch(function() {
            console.log('Something has occured');
        });
}

$(document).ready(function() {
    load_menu();
    click_logout();
    // click_shop();
});