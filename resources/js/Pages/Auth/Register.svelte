<script context="module">
    import GuestLayout, {title} from "@/Layouts/GuestLayout.svelte";

    export const layout = GuestLayout;
</script>

<script>
    import {Link, useForm} from '@inertiajs/svelte';
    import AuthenticationCard from "@/Components/Auth/AuthenticationCard.svelte";
    import SubmitButton from "@/Components/Auth/SubmitButton.svelte";
    import Text from "@/Components/FormLayouts/Inputs/Text.svelte";

    $title = 'Register';

    let form = useForm('Register', {
        name: null,
        email: null,
        password: null,
        password_confirmation: '',
        terms: false,
    });

    function register() {
        $form.post('/register', {
            onSuccess: () => $form.reset('password', 'password_confirmation'),
        });
    }
</script>

<AuthenticationCard>
    <form on:submit|preventDefault={register}>
        <div class="space-y-6">
            <Text name="name" label="Name" bind:value={$form.name} error={$form.errors.name} required autofocus
                  autocomplete="name"/>

            <Text name="email" label="Email address" type="email" bind:value={$form.email} error={$form.errors.email}
                  required/>

            <Text name="password" label="Password" type="password" bind:value={$form.password}
                  error={$form.errors.password} required
                  autocomplete="new-password"/>

            <Text name="password_confirmation" label="Confirm password" type="password"
                  bind:value={$form.password_confirmation} error={$form.errors.password_confirmation} required
                  autocomplete="new-password"/>
        </div>

        <div class="flex flex-col items-center mt-12">
            <SubmitButton title="Register"/>

            <Link href="/login" class="mt-4 text-sm text-gray-600 hover:text-gray-800 dark:text-gray-200 dark:hover:text-white">
                Already registered
            </Link>
        </div>
    </form>
</AuthenticationCard>
