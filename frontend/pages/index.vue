<template>
  <div class="container">
    <h1>Recipe Search 3000</h1>

    <div class="search-form">
      <div class="search-field">
        <label for="email">Author Email:</label>
        <input
          id="email"
          v-model="searchParams.email"
          type="email"
          placeholder="e.g., foo@bar.com"
        />
      </div>

      <div class="search-field">
        <label for="keyword">Keyword:</label>
        <input
          id="keyword"
          v-model="searchParams.keyword"
          type="text"
          placeholder="Search in name, description, ingredients, or steps"
        />
      </div>

      <div class="search-field">
        <label for="ingredients">Ingredients:</label>
        <select
          id="ingredients"
          v-model="searchParams.ingredients"
          multiple
          class="multiselect"
        >
          <option
            v-for="ingredient in availableIngredients"
            :key="ingredient.id"
            :value="ingredient.name"
          >
            {{ ingredient.name }}
          </option>
        </select>
        <div v-if="searchParams.ingredients.length > 0" class="selected-ingredients">
          <strong>Selected:</strong>
          <span
            v-for="(ingredient, index) in searchParams.ingredients"
            :key="index"
            class="ingredient-tag"
          >
            {{ ingredient }}
            <button
              type="button"
              @click="removeIngredient(index)"
              class="remove-ingredient"
              aria-label="Remove ingredient"
            >
              ×
            </button>
          </span>
        </div>
      </div>

      <div class="search-field">
        <label for="per_page">Results per page:</label>
        <select
          id="per_page"
          v-model="searchParams.per_page"
        >
          <option value="1">1</option>
          <option value="5">5</option>
          <option value="15">15</option>
          <option value="50">50</option>
        </select>
      </div>

      <button @click="performSearch" :disabled="loading">
        {{ loading ? 'Searching...' : 'Search' }}
      </button>
      <button @click="clearSearch" class="secondary">Clear</button>
    </div>

    <div v-if="error" class="error">
      {{ error }}
    </div>

    <div v-if="recipes && recipes.data && recipes.data.length > 0" class="results">
      <h2>Results ({{ recipes.meta.total }} found)</h2>

      <div class="recipe-list">
        <div v-for="recipe in recipes.data" :key="recipe.id" class="recipe-card">
          <NuxtLink :to="{ name: 'recipes-slug', params: { slug: recipe.slug } }" class="recipe-link">
            <h3>{{ recipe.name }}</h3>
            <p class="author">Author: {{ recipe.author_email }}</p>
            <p class="description">{{ recipe.description }}</p>
            <div class="ingredients">
              <strong>Ingredients:</strong>
              <ul>
                <li v-for="ingredient in recipe.ingredients" :key="ingredient.id">
                  {{ ingredient.name }}
                </li>
              </ul>
            </div>
          </NuxtLink>
        </div>
      </div>

      <div v-if="recipes.meta.last_page > 1" class="pagination">
        <button
          @click="goToPage(recipes.meta.current_page - 1)"
          :disabled="recipes.meta.current_page === 1"
        >
          Previous
        </button>
        <span>
          Page {{ recipes.meta.current_page }} of {{ recipes.meta.last_page }}
        </span>
        <button
          @click="goToPage(recipes.meta.current_page + 1)"
          :disabled="recipes.meta.current_page === recipes.meta.last_page"
        >
          Next
        </button>
      </div>
    </div>

    <div v-else-if="recipes && recipes.data && recipes.data.length === 0 && hasSearched" class="no-results">
      No recipes found matching your search criteria.
    </div>
  </div>
</template>

<script setup lang="ts">
import type { SearchResponse } from '~/composables/useRecipes'
import '~/assets/css/recipe-index.css'

const { searchRecipes, getIngredients } = useRecipes()

const searchParams = ref({
  email: '',
  keyword: '',
  ingredients: [] as string[],
  per_page: 15
})

const availableIngredients = ref<Array<{ id: number; name: string }>>([])
const recipes = ref<SearchResponse | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)
const hasSearched = ref(false)

const performSearch = () => {
  loading.value = true
  error.value = null
  hasSearched.value = true

  const params: any = {}
  if (searchParams.value.email) params.email = searchParams.value.email
  if (searchParams.value.keyword) params.keyword = searchParams.value.keyword
  if (searchParams.value.ingredients && searchParams.value.ingredients.length > 0) {
    params.ingredients = searchParams.value.ingredients
  }
  if (searchParams.value.per_page) params.per_page = Number(searchParams.value.per_page)

  searchRecipes(params)
    .then((response) => {
      recipes.value = response
    })
    .catch((e: any) => {
      error.value = e.message || 'An error occurred while searching recipes'
      recipes.value = null
    })
    .finally(() => {
      loading.value = false
    })
}

const removeIngredient = (index: number) => {
  searchParams.value.ingredients.splice(index, 1)
}

const clearSearch = () => {
  searchParams.value = {
    email: '',
    keyword: '',
    ingredients: [],
    per_page: 10
  }
  recipes.value = null
  hasSearched.value = false
  error.value = null
}

const goToPage = (page: number) => {
  if (page < 1 || (recipes.value && page > recipes.value.meta.last_page)) return

  loading.value = true
  error.value = null

  const params: any = { page }
  if (searchParams.value.email) params.email = searchParams.value.email
  if (searchParams.value.keyword) params.keyword = searchParams.value.keyword
  if (searchParams.value.ingredients && searchParams.value.ingredients.length > 0) {
    params.ingredients = searchParams.value.ingredients
  }
  if (searchParams.value.per_page) params.per_page = Number(searchParams.value.per_page)

  searchRecipes(params)
    .then((response) => {
      recipes.value = response
    })
    .catch((e: any) => {
      error.value = e.message || 'An error occurred while loading recipes'
    })
    .finally(() => {
      loading.value = false
    })
}

// Load ingredients and initial recipes on mount
onMounted(() => {
  getIngredients()
    .then((ingredients) => {
      availableIngredients.value = ingredients
    })
    .catch((e: any) => {
      console.error('Failed to load ingredients:', e)
    })
  performSearch()
})
</script>