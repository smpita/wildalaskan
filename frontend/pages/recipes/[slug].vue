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
            {{ ingredient.name }}
          </li>
        </ul>
      </div>

      <div v-if="recipe.steps && recipe.steps.length > 0" class="steps">
        <h2>Steps</h2>
        <ol>
          <li v-for="(step, index) in recipe.steps" :key="index">
            {{ step }}
          </li>
        </ol>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Ingredient {
  id: number
  name: string
}

interface Recipe {
  id: number
  name: string
  description: string
  author_email: string
  slug: string
  steps: string[]
  ingredients: Ingredient[]
}

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
      // Handle both wrapped and unwrapped responses
      const recipeData = (response as any)?.data || response
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

<style scoped>
.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.loading,
.error {
  text-align: center;
  padding: 40px;
  font-size: 18px;
}

.error {
  color: #721c24;
  background: #f8d7da;
  border-radius: 4px;
}

.back-link {
  display: inline-block;
  margin-bottom: 20px;
  color: #007bff;
  text-decoration: none;
  font-size: 14px;
}

.back-link:hover {
  text-decoration: underline;
}

.recipe-detail h1 {
  color: #333;
  margin-bottom: 20px;
}

.meta {
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 2px solid #eee;
}

.author {
  color: #666;
  font-size: 16px;
}

.description {
  margin-bottom: 30px;
}

.description h2 {
  color: #333;
  margin-bottom: 10px;
  font-size: 20px;
}

.description p {
  color: #555;
  line-height: 1.6;
}

.ingredients {
  margin-bottom: 30px;
}

.ingredients h2 {
  color: #333;
  margin-bottom: 15px;
  font-size: 20px;
}

.ingredients ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.ingredients li {
  padding: 10px 0;
  border-bottom: 1px solid #eee;
  color: #555;
}

.ingredients li:last-child {
  border-bottom: none;
}

.steps {
  margin-bottom: 30px;
}

.steps h2 {
  color: #333;
  margin-bottom: 15px;
  font-size: 20px;
}

.steps ol {
  padding-left: 20px;
}

.steps li {
  padding: 10px 0;
  color: #555;
  line-height: 1.6;
}
</style>

