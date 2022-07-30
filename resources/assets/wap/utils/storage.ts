const STORAGE_THEME = 'theme'
export const storageThemeGet = () => window.localStorage.getItem(STORAGE_THEME)
export const storageThemeSet = (theme) => (theme ? window.localStorage.setItem(STORAGE_THEME, theme) : window.localStorage.removeItem(STORAGE_THEME))

const STORAGE_TOKEN = 'token'
export const storageTokenGet = () => window.localStorage.getItem(STORAGE_TOKEN)
export const storageTokenSet = (token) => (token ? window.localStorage.setItem(STORAGE_TOKEN, token) : window.localStorage.removeItem(STORAGE_TOKEN))
