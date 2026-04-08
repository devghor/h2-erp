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
  <div class="mb-5">
    <!-- Top bar -->
    <div class="flex flex-wrap items-center justify-between gap-2">

      <!-- Left: filter toggle + active chips -->
      <div class="flex items-center gap-2">
        <UButton
          :color="activeFilters ? 'primary' : 'neutral'"
          :variant="activeFilters ? 'subtle' : 'outline'"
          size="sm"
          leading-icon="i-lucide-sliders-horizontal"
          :trailing-icon="showFilters ? 'i-lucide-chevron-up' : 'i-lucide-chevron-down'"
          class="font-medium"
          @click="showFilters = !showFilters"
        >
          Filters
          <template v-if="activeFilters" #trailing>
            <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-primary text-white text-[10px] font-bold leading-none">
              {{ activeFilters }}
            </span>
          </template>
        </UButton>

        <Transition
          enter-active-class="transition-all duration-150 ease-out"
          leave-active-class="transition-all duration-100 ease-in"
          enter-from-class="opacity-0 scale-95"
          enter-to-class="opacity-100 scale-100"
          leave-from-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-95"
        >
          <div v-if="activeFilters" class="flex items-center gap-1.5">
            <USeparator orientation="vertical" class="h-4" />
            <span class="text-xs text-muted">{{ activeFilters }} filter{{ activeFilters > 1 ? 's' : '' }} applied</span>
            <UButton
              size="xs"
              color="neutral"
              variant="ghost"
              icon="i-lucide-x"
              class="text-muted hover:text-default"
              @click="$emit('clear')"
            />
          </div>
        </Transition>
      </div>

      <!-- Right: action slot + column visibility -->
      <div class="flex items-center gap-2">
        <slot name="actions" />

        <USeparator v-if="$slots.actions" orientation="vertical" class="h-5" />

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
          <UButton size="sm" color="neutral" variant="ghost" trailing-icon="i-lucide-columns-3" class="font-medium text-muted">
            Columns
          </UButton>
        </UDropdownMenu>
      </div>
    </div>

    <!-- Filter panel -->
    <Transition
      enter-active-class="transition-all duration-200 ease-out"
      leave-active-class="transition-all duration-150 ease-in"
      enter-from-class="opacity-0 -translate-y-1"
      enter-to-class="opacity-100 translate-y-0"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-1"
    >
      <form
        v-if="showFilters"
        class="mt-3 rounded-xl border border-default bg-elevated/40 overflow-hidden"
        @submit.prevent="$emit('apply')"
      >
        <div class="px-4 py-3 border-b border-default bg-elevated/60 flex items-center justify-between">
          <div class="flex items-center gap-2 text-sm font-medium text-highlighted">
            <UIcon name="i-lucide-sliders-horizontal" class="size-4 text-muted" />
            Filter records
          </div>
          <UButton
            type="button"
            size="xs"
            color="neutral"
            variant="ghost"
            icon="i-lucide-x"
            class="text-muted"
            @click="showFilters = false"
          />
        </div>

        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <slot name="filters" />
        </div>

        <div class="px-4 py-3 border-t border-default bg-elevated/60 flex items-center justify-between">
          <p class="text-xs text-muted">Press <kbd class="px-1.5 py-0.5 rounded bg-accented text-default text-[11px] font-mono border border-default">Enter</kbd> in any field to apply</p>
          <div class="flex items-center gap-2">
            <UButton type="button" size="sm" color="neutral" variant="ghost" label="Reset" @click="$emit('clear')" />
            <UButton type="submit" size="sm" color="primary" variant="solid" label="Apply filters" icon="i-lucide-check" />
          </div>
        </div>
      </form>
    </Transition>
  </div>
</template>
