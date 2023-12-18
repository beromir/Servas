import {writable} from "svelte/store";

function createRefreshGroupsStorage() {
    const getData = () => {
        return null;
    };
    const {subscribe, set, update} = writable(getData());

    return {
        subscribe,
        set,
        update: () => update(() => Date.now()),
        reset: () => set(getData()),
    };
}

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
export const refreshGroups = createRefreshGroupsStorage();
export const selectedTags = createSelectedTagsStorage();
export const linkFilter = createLinkFilterStorage();
