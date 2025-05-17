import { createRouter, createWebHistory } from 'vue-router'
import store from '@/store'

const routes = [
  {
    path: '/',
    name: 'landing',
    component: () => import('@/components/LandingView.vue')
  },
  {
    path: '/home',
    name: 'home',
    component: () => import('@/views/HomeView.vue'),
    meta: {requiresAuth: true},
    children: [
      {
        path: 'dashboard',
        name: 'dashboard',
        component: () => import('@/components/DashBoardView.vue')
      },
      {
        path: 'map',
        name: 'map',
        component: () => import('@/views/MapAndForm.vue')
      },
      {
        path: 'perfil',
        name: 'perfil',
        component: () => import('@/components/ProfileView.vue')
      }
    ]
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/components/RegisterView.vue')
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/components/LoginView.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next)=>{
  const isAuth = store.getters['auth/isAuthenticated']

  if(to.matched.some(record => record.meta.requiresAuth)){
    if(!isAuth){
      next('/login')
    }else{
      next()
    }
  }else{
    next()
  }
})

export default router
