<template>
  <div class="container">
    <div v-if="loading" class="loading">Loading recipe...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="recipe" class="recipe-detail">
      <NuxtLink to="/" class="back-link">← Back to Search</NuxtLink>

      <h1>{{ recipe.name }}</h1>

      <div class="meta">
        <p class="author"><strong>Author:</strong> {{ recipe.author_email }}</p>
      </div>

      <div v-if="recipe.description" class="description">
        <h2>Description</h2>
        <p>{{ recipe.description }}</p>
      </div>

      <div class="ingredients">
        <h2>Ingredients</h2>
        <ul>
          <li v-for="ingredient in recipe.ingredients" :key="ingredient.id">
            {{ ingredient.amount }} {{ ingredient.unit }} {{ ingredient.name }}
          </li>
        </ul>
      </div>

      <div v-if="recipe.steps && recipe.steps.length > 0" class="steps">
        <h2>Steps</h2>
        <ol>
          <li v-for="step in recipe.steps" :key="step.step_number">
            {{ step.description }}
          </li>
        </ol>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Recipe } from '~/composables/useRecipes'
import '~/assets/css/recipe-detail.css'

const route = useRoute()
const { getRecipeBySlug } = useRecipes()

const recipe = ref<Recipe | null>(null)
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(() => {
  const slug = route.params.slug as string
  loading.value = true
  error.value = null

  getRecipeBySlug(slug)
    .then((response) => {
      const recipeData = (response as any).data
      recipe.value = recipeData as Recipe
    })
    .catch((e: any) => {
      error.value = e.message || 'Recipe not found'
    })
    .finally(() => {
      loading.value = false
    })
})
</script>