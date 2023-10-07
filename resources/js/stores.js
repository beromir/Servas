import {writable} from "svelte/store";

function createLinkFilterStorage() {
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

function createSelectedTagsStorage() {
    const getData = () => {
        return {tags: [], action: ''};
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
export const selectedTags = createSelectedTagsStorage();
export const linkFilter = createLinkFilterStorage();
