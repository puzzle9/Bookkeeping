import RouterComponent from './RouterComponent.vue'
import Layout from './Layout.vue'
import NavBar from './NavBar.vue'

export default {
    install(app) {
        app.component('RouterComponent', RouterComponent)
        app.component('Layout', Layout)
        app.component('NavBar', NavBar)
    },
}
