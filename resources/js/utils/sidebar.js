import {dispatchCustomEvent} from "@/utils/index.js";

function closeSidebar() {
    if (window.innerWidth >= 1024) {
        return;
    }

    dispatchCustomEvent('toggleSidebar');
}

export {closeSidebar};
