import {writable} from "svelte/store";

export const refreshLinks = writable(true);
export const refreshTags = writable(true);
export const refreshGroups = writable(true);
export const selectedTags = writable([]);
export const linkFilter = writable({tags: [], showUntaggedOnly: false});
