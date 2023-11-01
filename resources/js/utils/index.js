import {tick} from "svelte";

export const route = window.route

export function toggleValueInArray(arr, item) {
    return arr.includes(item) ? arr.filter(i => i !== item) : [...arr, item]
}

export function dispatchCustomEvent(event, data = null) {
    if (data !== null) {
        window.dispatchEvent(new CustomEvent(event, {detail: data}));
    } else {
        window.dispatchEvent(new CustomEvent(event));
    }
}

/** Dispatch event on click outside of node */
export function clickOutside(node) {

    const handleClick = event => {
        if (node && !node.contains(event.target) && !event.defaultPrevented) {
            node.dispatchEvent(
                new CustomEvent('click_outside', node)
            )
        }
    }

    document.addEventListener('click', handleClick, true);

    return {
        destroy() {
            document.removeEventListener('click', handleClick, true);
        }
    }
}

export function noScroll() {
    document.body.classList.add('overflow-hidden');
    document.body.classList.add('sm:pr-3');

    return {
        destroy() {
            document.body.classList.remove('overflow-hidden');
            document.body.classList.remove('sm:pr-3');
        }
    }
}

export async function focusSelf(node, focus = false) {
    if (focus) {
        await tick();

        node.focus();
    }
}
