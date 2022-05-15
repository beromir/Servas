<script>
    import {createEventDispatcher} from 'svelte';

    const dispatch = createEventDispatcher();

    export let type = 'button';

    $: props = {
        ...$$restProps,
    };
</script>

<button {...props} {type} on:click={() => dispatch('clicked')}
        class="{props.class || ''} inline-flex items-center px-4 py-2 bg-primary-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-600 focus:outline-none focus:border-primary-200 focus:ring focus:ring-primary-200 disabled:opacity-25 transition">
    <slot/>
</button>
