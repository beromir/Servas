import {writable} from "svelte/store";

function createLinkFilter() {
    const getData = () => {
        return {tags: [], showUntaggedOnly: false, isActive: false};
    };
    const {subscribe, set, update} = writable(getData());

    return {
        subscribe,
        set,
        update,
        reset: () => set(getData()),
    };
}

export const refreshLinks = writable(true);
export const refreshTags = writable(true);
export const refreshGroups = writable(true);
export const selectedTags = writable([]);
export const linkFilter = createLinkFilter();
