<script>
    export let classNames = '';
</script>

<div class={[classNames, 'px-4 sm:px-0'].join(' ').trim()}>
    <slot/>
</div>
