<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import Checkbox from "@/Jetstream/Checkbox.svelte";
    import {useForm} from "@inertiajs/svelte";

    export let user = {};

    let exportData = [];

    let exportUserDataForm = useForm({
        data: [],
    });
</script>

<JetFormSection>
    <svelte:fragment slot="title">
        Export User Data
    </svelte:fragment>

    <svelte:fragment slot="description">
        Export your data as a JSON file.
    </svelte:fragment>

    <svelte:fragment slot="form">
        <div class="grid grid-cols-2">
            <div>
                <label class="flex items-center">
                    <input type="checkbox" bind:group={exportData} value="links"
                           class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"/>
                    <span class="ml-2 text-sm text-gray-600">Links</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" bind:group={exportData} value="groups"
                           class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"/>
                    <span class="ml-2 text-sm text-gray-600">Groups</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" bind:group={exportData} value="tags"
                           class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"/>
                    <span class="ml-2 text-sm text-gray-600">Tags</span>
                </label>
            </div>
        </div>
    </svelte:fragment>

    <svelte:fragment slot="actions">
        <JetButton type="submit">
            Export
        </JetButton>
    </svelte:fragment>
</JetFormSection>
