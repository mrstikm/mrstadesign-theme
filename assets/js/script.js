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

    // po kliknuti na odkaz
    const handleClick = (event) => {
        
        // zrusit presmerovani
        event.preventDefault();
        // ziskat href atribut z odkazu
        let href = event.target.title;

        // AJAX
        httpRequest = new XMLHttpRequest();

        if (!httpRequest) {
            return false;
        }

        httpRequest.open('POST', MyAjax.ajaxurl, true);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.onreadystatechange = getNewGallery;
        httpRequest.send('action=change_category&security=mrstadesign_custom_ajax_nonce&href='+href);
        // konec

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
    
})();