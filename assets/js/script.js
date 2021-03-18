(function() {

    // ZMENA KATEGORII
    const handleClick = (event) => {
        // zrusit presmerovani
        event.preventDefault();
        
        // ajaxove volani na odkaz kategorie (akce v php, odkaz, element do ktereho se prida response)
        ajaxCall('change_category', event.target.title, 'gallery-set');
        
        // zruseni active u vsech polozek v menu
        document.querySelectorAll('#nav-primary > li').forEach(li => {
            li.classList.remove("current-menu-item");
        });
        
        // pridani active rodici targetu
        event.target.parentElement.classList.add("current-menu-item");
    };

    // listener pro menu
    document.querySelectorAll('#nav-primary > li > a').forEach(a => {
        a.addEventListener('click', handleClick);
    });



    // LIGHTBOX
    const handleLightbox = (event) => {
        // zrusit default akci
        event.preventDefault();
        if( !(event.target.nodeName == 'IMG') ) { return; }

        // ajaxove volani na odkaz prispevku (akce v php, odkaz, element do ktereho se prida response)
        ajaxCall('post_content', event.target.parentElement.parentElement.href, 'lightbox-content');
        
        openLightbox();
    };
    // zavreni lightboxu
    const closeLightbox = () => {
        document.getElementById('lightbox').style.display = "none";
    }
    // otevreni lightboxu
    const openLightbox = () => {
        document.getElementById('lightbox').style.display = "block";
    }
    // vytvoreni elementu img
    const createImg = (src) => {
        let img = document.createElement('img');
        img.setAttribute('src', src);
        return img;
    }
    // prirazeni img do lokace
    const appendImg = (img, location) => {
        let appendTo = document.getElementById(location);
        appendTo.innerHTML = '';
        appendTo.appendChild(img);
    }
    // hide / visible sipek
    const hideLeft = () => {
        document.getElementById('arrow-left').style.visibility = "hidden";
    }
    const showLeft = () => {
        document.getElementById('arrow-left').style.visibility = "visible";
    }
    const hideRight = () => {
        document.getElementById('arrow-right').style.visibility = "hidden";
    }
    const showRight = () => {
        document.getElementById('arrow-right').style.visibility = "visible";
    }

    // klikani na sipky
    const handleArrows = (event) => {
        event.preventDefault();

        let newPicture,
            actualPicture = document.getElementById("actual");
        // klik vlevo - prechozi sourozenec, klik vpravo nasledujici
        if (event.target.id == 'arrow-left') {
            newPicture = actualPicture.previousSibling;
            showRight();
        } else {
            newPicture = actualPicture.nextSibling;
            showLeft();
        }
        // vymazani actual ID pro odkazy a zmizeni sipek pro prvni / posledni fotku
        if ( newPicture == document.getElementById('slides').firstChild ) {
            hideLeft();
        } else if ( newPicture == document.getElementById('slides').lastChild ) {
            hideRight();
        }
        
        document.querySelectorAll('#slides > a').forEach(a => {
            a.setAttribute('id', '');
        });
        
        // nastaveni actual ID pro novy odkaz
        newPicture.setAttribute('id', 'actual');

        // zobrazeni obrazku
        let src = newPicture.attributes[0].value,
            img = createImg(src);
        appendImg(img, 'main-picture');
    }
    // klikani na slidy
    const handleSlides = (event) => {
        event.preventDefault();
        // vymazani actual ID pro odkazy
        document.querySelectorAll('#slides > a').forEach(a => {
            a.setAttribute('id', '');
        });

        let slides = document.getElementById('slides'),
        targetA = event.target.parentElement;
        
        // prirazeni actual ID pro kliknuty odkaz
        targetA.setAttribute('id', 'actual');
        if ( slides.firstChild == targetA ) {
            hideLeft();
            if ( !(slides.lastChild == targetA) ) {
                showRight();
            }
        } else if ( slides.lastChild == targetA ) {
            hideRight();
            if ( !(slides.firstChild == targetA) ) {
                showLeft();
            }
        } else {
            showLeft();
            showRight();
        }
        // zobrazeni obrazku
        let src = event.target.parentElement.attributes[0].value,
            img = createImg(src);
        appendImg(img, 'main-picture');
    };

    document.getElementById('gallery-set').addEventListener('click', handleLightbox);
    
    document.getElementById('lightbox').addEventListener('click', event => {
        if ( !(event.target.id == 'arrow-left' || event.target.id == 'arrow-right' || event.target.nodeName == 'IMG') ) {
            closeLightbox();
        } else if ( event.target.id == "arrow-right" || event.target.id == "arrow-left" ) { 
            handleArrows(event);
        } else if ( event.target.parentElement.parentElement.id == 'slides' ) {
            handleSlides(event);
        }
    });



    // AJAX
    const handleAjax = (elementId) => {
        try {
            if (httpRequest.readyState === XMLHttpRequest.DONE) {
                if (httpRequest.status === 200) {
                    let element = document.getElementById(elementId);
                    element.innerHTML = httpRequest.response;
                    if ( elementId == 'lightbox-content') {
                        if ( document.querySelectorAll('#slides > a').length < 2) {
                            document.querySelectorAll('#arrow-left, #arrow-right').forEach(item => {
                                item.style.visibility = "hidden";
                            })
                        }
                    } else {
                        elementsShow = document.querySelectorAll('#gallery-set > a > figure');
                    }
                } else {
                    alert("failed");
                }      
            }
        } catch (e) {
             alert('Exception: ' + e.description);
        }
    };

    let httpRequest;

    const ajaxCall = (action, href, element) => {
        httpRequest = new XMLHttpRequest();
        
        if (!httpRequest) {
            return false;
        }

        httpRequest.open('POST', MyAjax.ajaxurl, true);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.onreadystatechange = function() {
            handleAjax(element);
        }
        httpRequest.send('action=' + action + '&security=mrstadesign_custom_ajax_nonce&href=' + href);
        document.getElementById(element).innerHTML = '<div class="loader"></div>';
    }



    // ANIMACE OBRAZKU
    let scroll = window.requestAnimationFrame || function(callback) { window.setTimeout(callback, 1000/60) },
        elementsShow = document.querySelectorAll('#gallery-set > a > figure');

    const isElementInViewport = (element) => {
        let rect = element.getBoundingClientRect();

        return (
            (rect.top <= 0
                && rect.bottom >= 0)
              ||
              (rect.bottom >= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.top <= (window.innerHeight || document.documentElement.clientHeight))
              ||
              (rect.top >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight))
        );
    }

    const loop = () => {

        elementsShow.forEach(element => {
            if (isElementInViewport(element)) {
                element.classList.add('is-visible');
            } else {
                element.classList.remove('is-visible');
            }
        });
        
        scroll(loop);
    }

    loop();

})();