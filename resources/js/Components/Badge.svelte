<script>
    import {createEventDispatcher} from 'svelte';

    const dispatch = createEventDispatcher();

    /**
     * @typedef {Object} Props
     * @property {any} title
     * @property {string} [color]
     * @property {boolean} [large]
     * @property {boolean} [noHover]
     * @property {boolean} [rounded]
     */

    /** @type {Props & { [key: string]: any }} */
    let {
        title,
        color = '',
        large = true,
        noHover = false,
        rounded = false,
        ...rest
    } = $props();

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

    let sizeClasses = $derived(large ? 'px-3 py-0.5 text-sm decoration-[1.4px]' : 'px-2.5 py-0.5 text-xs decoration-[1.1px]');
    let hoverClasses = $derived(noHover ? '' : 'underline-offset-2 hover:underline hover:cursor-pointer');
    let roundedClasses = $derived(rounded ? 'rounded' : 'rounded-full');
</script>

<span onclick={() => dispatch('clicked')} {...rest}
      class={['inline-flex items-center font-medium select-none',
      rest.class, getColorClasses(), sizeClasses, hoverClasses, roundedClasses].join(' ').trim()}>
    {title}
</span>
