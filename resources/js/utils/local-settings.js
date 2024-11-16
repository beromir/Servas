function getLocalSettings() {
    const settings = localStorage.getItem('servas_settings');

    return settings !== null ? JSON.parse(settings) : {};
}

function setLocalSettings(name, settingsToSet) {
    let settings = getLocalSettings();

    switch (name) {
        case 'openedGroups':
            settings.openedGroups = settingsToSet;
            break;

        case 'sidebarIsOpen':
            settings.sidebarIsOpen = settingsToSet;
            break;

        case 'theme':
            settings.theme = settingsToSet;
            break;
    }

    localStorage.setItem('servas_settings', JSON.stringify(settings));
}

function getOpenedGroups() {
    const settings = getLocalSettings();

    return settings.openedGroups ?? [];
}

function toggleOpenedGroup(group) {
    let openedGroups = getOpenedGroups();

    const index = openedGroups.indexOf(group);

    if (index === -1) {
        openedGroups.push(group);
    } else {
        openedGroups.splice(index, 1);
    }

    setLocalSettings('openedGroups', openedGroups);

    return openedGroups;
}

function sidebarIsOpen() {
    const settings = getLocalSettings();

    return settings.sidebarIsOpen ?? true;
}

function toggleSidebar() {
    let showSidebar = sidebarIsOpen();

    showSidebar = !showSidebar;

    setLocalSettings('sidebarIsOpen', showSidebar);

    return showSidebar;
}

function getTheme() {
    const settings = getLocalSettings();

    return settings.theme ?? '';
}

function setTheme(theme) {
    setLocalSettings('theme', theme);
}

export {getOpenedGroups, toggleOpenedGroup, sidebarIsOpen, toggleSidebar, getTheme, setTheme};
