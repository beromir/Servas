<script>
    import {createEventDispatcher} from 'svelte';

    const dispatch = createEventDispatcher();

    export let title = '';
    export let color = '';

    let textColorClassNames = '';
    let iconColorClassNames = '';

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

<button type="button" on:click={() => {dispatch('clicked')}}
        class={['flex items-center px-4 py-2 w-full group text-gray-700 text-sm hover:bg-gray-100 dark:text-gray-50 dark:hover:bg-gray-800', textColorClassNames].join(' ').trim()}
        role="menuitem" tabindex="-1" id="menu-item-0">
    {#if $$slots.icon}
        <span class={['mr-3 size-5 text-gray-500 dark:text-gray-400', iconColorClassNames].join(' ').trim()}>
            <slot name="icon"/>
        </span>
    {/if}
    {title}
</button>
