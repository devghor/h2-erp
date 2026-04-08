<script setup lang="ts">
import { upperFirst } from 'scule'

defineProps<{
  tableApi?: any
  activeFilters?: number
}>()

defineEmits<{
  apply: []
  clear: []
}>()

const showFilters = ref(false)
</script>

<template>
  <div>
    <!-- Top bar -->
    <div class="flex items-center justify-between gap-2">

      <!-- Left -->
      <div class="flex items-center gap-1.5">
        <UButton
          :color="activeFilters ? 'primary' : 'neutral'"
          :variant="activeFilters ? 'subtle' : 'ghost'"
          size="xs"
          leading-icon="i-lucide-sliders-horizontal"
          :trailing-icon="showFilters ? 'i-lucide-chevron-up' : 'i-lucide-chevron-down'"
          @click="showFilters = !showFilters"
        >
          Filters
          <template v-if="activeFilters" #trailing>
            <span class="inline-flex items-center justify-center w-3.5 h-3.5 rounded-full bg-primary text-white text-[9px] font-bold leading-none">
              {{ activeFilters }}
            </span>
          </template>
        </UButton>

        <template v-if="activeFilters">
          <USeparator orientation="vertical" class="h-3.5" />
          <span class="text-xs text-muted">{{ activeFilters }} applied</span>
          <UButton size="xs" color="neutral" variant="ghost" icon="i-lucide-x" class="text-muted" @click="$emit('clear')" />
        </template>
      </div>

      <!-- Right -->
      <div class="flex items-center gap-1.5">
        <slot name="actions" />

        <USeparator v-if="$slots.actions" orientation="vertical" class="h-3.5" />

        <UDropdownMenu
          v-if="tableApi"
          :items="
            tableApi
              .getAllColumns()
              .filter((col: any) => col.getCanHide())
              .map((col: any) => ({
                label: upperFirst(col.id),
                type: 'checkbox' as const,
                checked: col.getIsVisible(),
                onUpdateChecked(checked: boolean) {
                  tableApi.getColumn(col.id)?.toggleVisibility(!!checked)
                },
                onSelect(e?: Event) { e?.preventDefault() }
              }))
          "
          :content="{ align: 'end' }"
        >
          <UButton size="xs" color="neutral" variant="ghost" trailing-icon="i-lucide-columns-3" class="text-muted">
            Columns
          </UButton>
        </UDropdownMenu>
      </div>
    </div>

    <!-- Filter panel -->
    <Transition
      enter-active-class="transition-all duration-150 ease-out"
      leave-active-class="transition-all duration-100 ease-in"
      enter-from-class="opacity-0 -translate-y-1"
      enter-to-class="opacity-100 translate-y-0"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-1"
    >
      <form
        v-if="showFilters"
        class="mt-2 rounded-lg border border-default bg-elevated/50 overflow-hidden"
        @submit.prevent="$emit('apply')"
      >
        <div class="px-3 py-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
          <slot name="filters" />
        </div>

        <div class="px-3 py-2 border-t border-default bg-elevated flex items-center justify-between">
          <p class="text-xs text-muted hidden sm:block">
            Press <kbd class="px-1 py-px rounded bg-accented text-[10px] font-mono border border-default">↵ Enter</kbd> to apply
          </p>
          <div class="flex items-center gap-1.5 ml-auto">
            <UButton type="button" size="xs" color="neutral" variant="ghost" label="Reset" @click="$emit('clear')" />
            <UButton type="submit" size="xs" color="primary" variant="solid" label="Apply" icon="i-lucide-check" />
          </div>
        </div>
      </form>
    </Transition>
  </div>
</template>
