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
      },
      {
        path: 'admin',
        name: 'AdminUser',
        component: () => import('@/components/AdminUser.vue'),
        meta: { requiresAuth: true, requiresAdmin: true}
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
  },
  {
    path: '/guest',
    name: 'guest',
    component: () => import('@/components/PublicMap.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next)=>{
  const isAuth = store.getters['auth/isAuthenticated']
  const isAdmin = store.getters['auth/isAdmin']

  if(to.matched.some(record => record.meta.requiresAuth)){
    if(!isAuth){
      next('/login')
    }
    
    if(to.matched.some(record => record.meta.requiresAdmin)){
      if(!isAdmin){
        return next('/home')
      }
    }
    
    return next()
  }
  return next()
})

export default router
