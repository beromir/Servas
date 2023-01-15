<script>
    export let title = '';
</script>

<div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <h3 class="text-lg font-medium leading-6 text-gray-900">{title}</h3>
            <p class="mt-1 text-sm text-gray-500">
                <slot name="description"/>
            </p>
        </div>
        <div class="mt-5 space-y-6 md:col-span-2 md:mt-0">
            <slot/>
        </div>
    </div>
</div>
