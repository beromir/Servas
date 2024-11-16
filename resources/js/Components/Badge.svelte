<script>
    import {createEventDispatcher} from 'svelte';

    const dispatch = createEventDispatcher();

    export let title;
    export let color = '';
    export let large = true;
    export let noHover = false;
    export let rounded = false;

    function getColorClasses() {
        switch (color) {
            case 'red':
                return 'bg-red-100 text-red-800';
            case 'gray':
                return 'bg-gray-200 text-gray-800 dark:!bg-white/10 dark:text-gray-200';
            case 'blue':
                return 'bg-blue-100 text-blue-800';
            case 'none':
                return '';
            default:
                return '!bg-primary-100 text-primary-800 dark:!bg-primary-700/80 dark:text-primary-50';
        }
    }

    $: sizeClasses = large ? 'px-3 py-0.5 text-sm decoration-[1.4px]' : 'px-2.5 py-0.5 text-xs decoration-[1.1px]';
    $: hoverClasses = noHover ? '' : 'underline-offset-2 hover:underline hover:cursor-pointer';
    $: roundedClasses = rounded ? 'rounded' : 'rounded-full';
</script>

<span on:click={() => dispatch('clicked')} {...$$restProps}
      class={['inline-flex items-center font-medium select-none',
      $$restProps.class, getColorClasses(), sizeClasses, hoverClasses, roundedClasses].join(' ').trim()}>
    {title}
</span>
