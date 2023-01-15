<script>
    // import JetActionMessage from '@/Jetstream/ActionMessage.vue';
    import JetButton from '@/Jetstream/Button.svelte';
    import JetFormSection from '@/Jetstream/FormSection.svelte';
    import JetInput from '@/Jetstream/Input.svelte';
    import JetInputError from '@/Jetstream/InputError.svelte';
    import JetLabel from '@/Jetstream/Label.svelte';
    import {useForm} from "@inertiajs/svelte";
    import {route} from "@/utils";

    let passwordInput;
    let currentPasswordInput;

    let form = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    function updatePassword() {
        $form.put(route('user-password.update'), {
            errorBag: 'updatePassword',
            preserveScroll: true,
            onSuccess: () => $form.reset(),
            onError: () => {
                if ($form.errors.password) {
                    $form.reset('password', 'password_confirmation');
                    passwordInput.focus();
                }

                if ($form.errors.current_password) {
                    $form.reset('current_password');
                    currentPasswordInput.focus();
                }
            }
        })
    }
</script>

<JetFormSection on:submitted={updatePassword}>
    <svelte:fragment slot="title">
        Update Password
    </svelte:fragment>

    <svelte:fragment slot="description">
        Ensure your account is using a long, random password to stay secure.
    </svelte:fragment>

    <svelte:fragment slot="form">
        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="current_password" label="Current Password"/>
            <JetInput id="current_password" type="password" class="mt-1 block w-full"
                      bind:value={$form.current_password}
                      bind:this={currentPasswordInput} autocomplete="current-password"/>
            <JetInputError message={$form.errors.current_password} class="mt-2"/>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="password" label="New Password"/>
            <JetInput id="password" type="password" class="mt-1 block w-full" bind:value={$form.password}
                      bind:this={passwordInput}
                      autocomplete="new-password"/>
            <JetInputError message={$form.errors.password} class="mt-2"/>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <JetLabel id="password_confirmation" label="Confirm Password"/>
            <JetInput id="password_confirmation" type="password" class="mt-1 block w-full"
                      bind:value={$form.password_confirmation} autocomplete="new-password"/>
            <JetInputError message={$form.errors.password_confirmation} class="mt-2"/>
        </div>
    </svelte:fragment>

    <svelte:fragment slot="actions">
        <!--
        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
            Saved.
        </jet-action-message>
        -->

        <JetButton type="submit" class={$form.processing ? 'opacity-25' : ''} disabled={$form.processing}>
            Save
        </JetButton>
    </svelte:fragment>
</JetFormSection>
