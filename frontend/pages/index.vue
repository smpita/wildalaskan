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
          <NuxtLink :to="`/recipes/${recipe.slug}`" class="recipe-link">
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

interface Recipe {
  id: number
  name: string
  description: string
  author_email: string
  slug: string
  ingredients: Array<{ id: number; name: string }>
}

const { searchRecipes, getIngredients } = useRecipes()

const searchParams = ref({
  email: '',
  keyword: '',
  ingredients: [] as string[]
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
    ingredients: []
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

<style scoped>
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  text-align: center;
  color: #333;
  margin-bottom: 30px;
}

.search-form {
  background: #f5f5f5;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 30px;
}

.search-field {
  margin-bottom: 15px;
}

.search-field label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
  color: #555;
}

.search-field input,
.search-field select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.search-field select.multiselect {
  min-height: 150px;
}

.selected-ingredients {
  margin-top: 10px;
  padding: 10px;
  background: #f8f9fa;
  border-radius: 4px;
}

.ingredient-tag {
  display: inline-block;
  background: #007bff;
  color: white;
  padding: 5px 10px;
  margin: 5px 5px 5px 0;
  border-radius: 4px;
  font-size: 12px;
}

.remove-ingredient {
  background: transparent;
  border: none;
  color: white;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  padding: 0 5px;
  margin-left: 5px;
  line-height: 1;
}

.remove-ingredient:hover {
  color: #ffcccc;
}

button {
  padding: 10px 20px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  margin-right: 10px;
}

button:hover:not(:disabled) {
  background: #0056b3;
}

button:disabled {
  background: #ccc;
  cursor: not-allowed;
}

button.secondary {
  background: #6c757d;
}

button.secondary:hover {
  background: #5a6268;
}

.error {
  background: #f8d7da;
  color: #721c24;
  padding: 15px;
  border-radius: 4px;
  margin-bottom: 20px;
}

.results h2 {
  margin-bottom: 20px;
  color: #333;
}

.recipe-list {
  display: grid;
  gap: 20px;
}

.recipe-card {
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  transition: box-shadow 0.3s;
}

.recipe-card:hover {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.recipe-link {
  text-decoration: none;
  color: inherit;
}

.recipe-card h3 {
  margin: 0 0 10px 0;
  color: #007bff;
}

.author {
  color: #666;
  font-size: 14px;
  margin: 5px 0;
}

.description {
  color: #555;
  margin: 10px 0;
}

.ingredients {
  margin-top: 15px;
}

.ingredients ul {
  list-style: none;
  padding: 0;
  margin: 10px 0 0 0;
}

.ingredients li {
  padding: 5px 0;
  border-bottom: 1px solid #eee;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
  margin-top: 30px;
}

.no-results {
  text-align: center;
  padding: 40px;
  color: #666;
  font-size: 18px;
}
</style>

