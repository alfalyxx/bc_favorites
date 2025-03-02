document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".favorite-toggle").forEach(btn => {
        btn.addEventListener("click", function() {
            let entity = this.dataset.entity;
            let entityId = this.dataset.id;
            let isFavorite = this.dataset.favorite === "true";
            let addText = this.dataset.addText;
            let removeText = this.dataset.removeText;
            let iconColor = this.dataset.iconColor || "#cccccc";
            let iconActiveColor = this.dataset.iconActiveColor || "#ff0000";

            BX.ajax.runComponentAction('alfa:favorites', 'toggleFavorite', {
                mode: 'class',
                data: { entity, entityId, action: isFavorite ? "remove" : "add" }
            }).then(response => {
                if (response.data.success) {
                    this.dataset.favorite = isFavorite ? "false" : "true";
                    let newState = this.dataset.favorite === "true";

                    this.setAttribute("title", newState ? removeText : addText);

                    let textContainer = this.querySelector(".favorite-text");
                    if (textContainer) {
                        textContainer.textContent = newState ? removeText : addText;
                    }

                    let icon = this.querySelector("svg path");
                    if (icon) {
                        icon.setAttribute("fill", newState ? iconActiveColor : iconColor);
                    }
                }
            });
        });
    });
});
