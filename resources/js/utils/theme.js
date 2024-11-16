import {getTheme, setTheme} from "@/utils/local-settings.js";

export function initTheme() {
    const theme = getTheme();

    return changeTheme(theme);
}

export function changeTheme(theme = '') {
    if (theme !== '') {
        applyTheme(theme);
        setTheme(theme);
        return theme;
    }

    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    applyTheme(systemTheme);
    setTheme('');
    return '';
}

function applyTheme(theme) {
    document.documentElement.classList.toggle('dark', theme === 'dark');
}
