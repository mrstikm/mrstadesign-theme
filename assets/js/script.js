(function() {

    let httpRequest;

    // vystrizeni gallery-setu z html
    const parseResponse = (content) => {
        var x = content.indexOf('<div id="gallery-set"');
        x = content.indexOf(">", x);    
        var y = content.lastIndexOf("</div>"); 
        return content.slice(x + 1, y);
    }

    // AJAX request
    const getNewGallery = () => {
        try {
            if (httpRequest.readyState === XMLHttpRequest.DONE) {
                if (httpRequest.status === 200) {
                    let gallerySet = document.getElementById('gallery-set');
                    gallerySet.innerHTML = httpRequest.response;
                } else {
                    alert("failed");
                }      
            }
        } catch (e) {
             alert('Exception: ' + e.description);
        }
    };

    const getPostContent = () => {
        try {
            if (httpRequest.readyState === XMLHttpRequest.DONE) {
                if (httpRequest.status === 200) {
                    let html = httpRequest.response;
                    document.getElementById('lightbox-content').innerHTML = html;
                } else {
                    alert("failed");
                }      
            }
        } catch (e) {
             alert('Exception: ' + e.description);
        }
    };

    // po kliknuti na odkaz
    const handleClick = (event) => {
        // zrusit presmerovani
        event.preventDefault();
        
        // ziskat href atribut z odkazu
        let href = event.target.title;
        
        ajaxCall('change_category', href, getNewGallery);
        
        // zruseni active u vsech polozek v menu
        document.querySelectorAll('#nav-primary > li').forEach(li => {
            li.classList.remove("current-menu-item");
        });
        
        // pridani active rodici targetu
        event.target.parentElement.classList.add("current-menu-item");
    };

    document.querySelectorAll('#nav-primary > li > a').forEach(a => {
        a.addEventListener('click', handleClick);
    });

    // LIGHTBOX
    
    const handleLightbox = (event) => {
        event.preventDefault();
        ajaxCall('post_content',event.target.parentElement.href, getPostContent);
        openLightbox();
    };
    const closeLightbox = () => {
        document.getElementById('lightbox').style.display = "none";
    }
    const openLightbox = () => {
        document.getElementById('lightbox').style.display = "block";
    }
    const handleArrows = (event) => {
        event.preventDefault();
        console.log(event);


    }
    const handleSlides = (event) => {
        event.preventDefault();
        let src = event.target.parentElement.attributes[0].value,
            img = document.createElement('img'),
            mainPicture = document.getElementById('main-picture');
        img.setAttribute('src', src);
        console.log(img);
        mainPicture.innerHTML = '';
        mainPicture.appendChild(img);
    }

    document.getElementById('gallery-set').addEventListener('click', handleLightbox);
    document.getElementById('lightbox').addEventListener('click', event => {
        if ( !(event.target.nodeName == 'IMG' || event.target.id == "arrow-left" || event.target.id == "arrow-right") ) {
            closeLightbox();
        } else if ( event.target.id == "arrow-right" || event.target.id == "arrow-left" ) { 
            handleArrows(event);
        } else if ( event.target.parentElement.parentElement.id == "slides" ){
            handleSlides(event);
        }
    });

    // AJAX
    const ajaxCall = (action, href, handle) => {
        httpRequest = new XMLHttpRequest();
        
        if (!httpRequest) {
            return false;
        }
        
        httpRequest.open('POST', MyAjax.ajaxurl, true);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.onreadystatechange = handle;
        httpRequest.send('action=' + action + '&security=mrstadesign_custom_ajax_nonce&href=' + href);
    }
    
})();