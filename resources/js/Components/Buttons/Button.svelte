<script>
    import {focusSelf} from "@/utils";
    import clsx from "clsx";

    let {
        type = 'button',
        title = null,
        color = undefined,
        hoverTitle = '',
        focusButton = false,
        icon,
        clicked,
        ...rest
    } = $props();

    function getColors() {
        switch (color) {
            case 'red':
                return clsx(
                    'border-transparent text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
                    '[&>svg]:fill-white dark:[&>svg]:fill-gray-950',
                );
            case 'white':
                return clsx(
                    'border-gray-400 text-gray-700 bg-white hover:bg-gray-50 focus:ring-primary-400 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:hover:bg-gray-700',
                    '[&>svg]:fill-gray-500 dark:[&>svg]:fill-gray-300',
                );
            default:
                return clsx(
                    'border-transparent text-white bg-primary-500 hover:bg-primary-600 focus:ring-primary-400',
                    '[&>svg]:fill-gray-100',
                );
        }
    }
</script>

<button
    {...rest} {type}
    onclick={clicked}
    class={clsx(
        'w-full inline-flex justify-center items-center py-2 border shadow-sm text-sm font-medium rounded-md select-none focus:outline-none focus:ring-2 focus:ring-offset-2 md:w-auto dark:focus:ring-offset-gray-900',
        '[&>svg]:size-5',
        title ? 'px-4 [&>svg]:-ml-1 [&>svg]:mr-2' : 'px-2',
        getColors(),
        rest.class
    )}
    use:focusSelf={focusButton}
    title={hoverTitle}>
    {#if icon}
        {@render icon?.()}
    {/if}
    {#if title}
        {title}
    {/if}
</button>
