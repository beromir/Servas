<script>
    import {createEventDispatcher} from 'svelte';
    import {focusSelf} from "@/utils";

    const dispatch = createEventDispatcher();
    export let type = 'button';
    export let title = null;
    export let color;
    export let focusButton = false;

    function getColors() {
        switch (color) {
            case 'red':
                return 'border-transparent text-white bg-red-600 hover:bg-red-700 focus:ring-red-500';
            case 'white':
                return 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-primary-400';
            default:
                return 'border-transparent text-white bg-primary-500 hover:bg-primary-600 focus:ring-primary-400';
        }
    }
</script>

<button
    {...$$restProps} {type}
    on:click={() => {dispatch('clicked')}}
    class={['w-full inline-flex justify-center items-center px-4 py-2 border shadow-sm text-sm font-medium rounded-md select-none focus:outline-none focus:ring-2 focus:ring-offset-2 md:w-auto',
    getColors(), $$restProps.class].join(' ').trim()}
    use:focusSelf={focusButton}>
    {#if $$slots.icon}
        <span class={['h-5 w-5', title ? '-ml-1 mr-2' : ''].join(' ').trim()}>
            <slot name="icon"/>
        </span>
    {/if}
    {#if title}
        {title}
    {/if}
</button>
