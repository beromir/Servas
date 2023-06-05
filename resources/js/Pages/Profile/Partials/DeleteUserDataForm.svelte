<script>
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import {useForm} from "@inertiajs/svelte";
    import DangerButton from "@/Jetstream/DangerButton.svelte";

    let deleteOptions = [
        'links',
        'groups',
        'tags',
    ];

    let form = useForm({
        deleteOptions: [],
    })

    function deleteUserData() {
        $form.post(route('delete-user-data'), {
            preserveScroll: true,
            onSuccess: () => {
                $form.reset();
            }
        });
    }
</script>

<JetFormSection on:submitted={deleteUserData}>
    <svelte:fragment slot="title">
        Delete Data
    </svelte:fragment>

    <svelte:fragment slot="description">
        Delete your account data.
    </svelte:fragment>

    <svelte:fragment slot="form">
        <div class="col-span-3 space-y-6">
            <div>
                {#each deleteOptions as option}
                    <label class="flex items-center">
                        <input type="checkbox" value={option} bind:group={$form.deleteOptions}
                               class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"/>
                        <span class="ml-2 text-sm text-gray-600 capitalize">{option}</span>
                    </label>
                {/each}
            </div>
        </div>
    </svelte:fragment>

    <svelte:fragment slot="actions">
        <DangerButton type="submit" disabled={!$form.deleteOptions.length}>
            Delete
        </DangerButton>
    </svelte:fragment>
</JetFormSection>
