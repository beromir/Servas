<script>
    export let title = '';
    export let withBackground = true;
    export let fixedHeight = false;
</script>

<div>
    <h3 class="font-medium text-gray-700">{title}</h3>
    <div class={[
         'relative mt-3 overflow-hidden',
         withBackground ? 'bg-white shadow rounded-lg' : '',
         fixedHeight ? 'h-[30rem]' : '',
         ].join(' ').trim()}>
        <slot/>
    </div>
</div>
