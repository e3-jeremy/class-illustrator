<script>
    const shadowClass = ["shadow"];
    const borderClass = ["border", "border-primary"];
    const backgroundClass = ["bg-info", "bg-gradient", "info-visible"];
    const toHide = ["d-block", "d-none"];
    const toShow = ["d-none", "d-block"];
    function hideAll(parent, methodInfo, elem) {
        const divs = document.querySelectorAll(".method-info");
        for (i = 0; i < divs.length; ++i) {
            if(methodInfo != divs[i]) {
                divs[i].classList.replace(...toHide); // Hide other element
                // Adjust position
                adjustPosition(divs[i], null);
            }
        }

        const cards = document.querySelectorAll(".card");
        for (i = 0; i < cards.length; ++i) {
            if(parent != cards[i]) cards[i].classList.remove(...shadowClass); // Remove shadow of other element
        }

        const cardHeaders = document.querySelectorAll(".card-header");
        for (i = 0; i < cardHeaders.length; ++i) {
            if(elem != cardHeaders[i]) cardHeaders[i].classList.remove(...backgroundClass); // Remove background of other element
        }
    }

    function toggleContent(elem) {
        const parent = elem.closest(".card");
        const methodInfo  = parent.children[1];

        hideAll(parent, methodInfo, elem);

        if(!parent.classList.contains("shadow")) parent.classList.add(...shadowClass);
        else parent.classList.remove(...shadowClass);

        if (!elem.classList.contains("bg-info")) elem.classList.add(...backgroundClass);
        else elem.classList.remove(...backgroundClass);

        if (methodInfo .classList.contains("d-none")) methodInfo .classList.replace(...toShow);
        else methodInfo .classList.replace(...toHide);
        // Adjust position
        adjustPosition(methodInfo, elem.querySelector('.fake-footer'));
    }

    function adjustPosition(element, element2) {
        const viewportHeight = window.innerHeight;
        const rect = element.getBoundingClientRect();

        if ((viewportHeight - 30) < rect.height) return;

        if (rect.bottom > viewportHeight) element.style.top = `-${(rect.height - (viewportHeight - rect.top)) + 30}px`;
        else element.style.top = '-18px';

        if(!element2) return;

        const rect2 = element2.getBoundingClientRect();
        if (rect.bottom > viewportHeight) {
            console.log(viewportHeight)
            console.log(rect2)
        }
        if (rect2.bottom + 30 > viewportHeight) element.style.borderBottom = `none`;
        else element.style.borderBottom = `2px solid #0086b3`;
    }

    var triggerTabList = [].slice.call(document.querySelectorAll("#classList a"));
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)

        triggerEl.addEventListener("click", function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
</script>