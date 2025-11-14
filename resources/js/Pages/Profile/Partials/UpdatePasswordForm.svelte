<script>
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import JetInputError from '@/Jetstream/InputError.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";
    import Input from "@/Components/Input.svelte";

    const passwordForm = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    function updatePassword() {
        $passwordForm.put(route('user-password.update'), {
            errorBag: 'updatePassword',
            preserveScroll: true,
            onSuccess: () => $passwordForm.reset(),
            onError: () => {
                if ($passwordForm.errors.password) {
                    $passwordForm.reset('password', 'password_confirmation');
                }

                if ($passwordForm.errors.current_password) {
                    $passwordForm.reset('current_password');
                }
            }
        })
    }
</script>

<JetFormSection submitted={updatePassword}>
    {#snippet title()}
        Update Password
    {/snippet}

    {#snippet description()}
        Ensure your account is using a long, random password to stay secure.
    {/snippet}

    {#snippet form()}
        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="current_password" label="Current Password"/>
            <Input id="current_password" type="password" autocomplete="current-password"
                   bind:value={$passwordForm.current_password} class="mt-1"/>
            <JetInputError message={$passwordForm.errors.current_password} class="mt-2"/>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="password" label="New Password"/>
            <Input id="password" type="password" autocomplete="new-password" bind:value={$passwordForm.password}
                   class="mt-1"/>
            <JetInputError message={$passwordForm.errors.password} class="mt-2"/>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="password_confirmation" label="Confirm Password"/>
            <Input id="password_confirmation" type="password" autocomplete="new-password"
                   bind:value={$passwordForm.password_confirmation} class="mt-1"/>
            <JetInputError message={$passwordForm.errors.password_confirmation} class="mt-2"/>
        </div>
    {/snippet}

    {#snippet actions()}
        <JetButton type="submit" class={$passwordForm.processing ? 'opacity-25' : ''}
                   disabled={$passwordForm.processing}>
            Save
        </JetButton>
    {/snippet}
</JetFormSection>
