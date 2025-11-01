<script>
    import {createEventDispatcher} from 'svelte';

    const dispatch = createEventDispatcher();

    /**
     * @typedef {Object} Props
     * @property {string} [title]
     * @property {string} [color]
     * @property {import('svelte').Snippet} [icon]
     */

    /** @type {Props} */
    let { title = '', color = '', icon } = $props();

    let textColorClassNames = $state('');
    let iconColorClassNames = $state('');

    switch (color) {
        case 'alert':
            textColorClassNames = 'hover:text-red-700 dark:hover:text-red-400';
            iconColorClassNames = 'group-hover:text-red-500';
            break;
        default:
            textColorClassNames = '';
            iconColorClassNames = '';
    }
</script>

<button type="button" onclick={() => {dispatch('clicked')}}
        class={['flex items-center px-4 py-2 w-full group text-gray-700 text-sm hover:bg-gray-100 dark:text-gray-50 dark:hover:bg-gray-800', textColorClassNames].join(' ').trim()}
        role="menuitem" tabindex="-1" id="menu-item-0">
    {#if icon}
        <span class={['mr-3 size-5 text-gray-500 dark:text-gray-400', iconColorClassNames].join(' ').trim()}>
            {@render icon?.()}
        </span>
    {/if}
    {title}
</button>
