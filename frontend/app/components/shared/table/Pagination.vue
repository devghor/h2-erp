<script setup lang="ts">
const props = defineProps<{
  selected: number
  total: number
  pagination: { pageIndex: number; pageSize: number }
}>()

const emit = defineEmits<{
  (e: 'update:pagination', val: { pageIndex: number; pageSize: number }): void
}>()
</script>

<template>
  <div class="flex items-center justify-between gap-3 border-t border-default pt-4 mt-auto">
    <div class="text-sm text-muted">
      {{ selected }} of {{ total }} row(s) selected.
    </div>
    <UPagination
      :default-page="pagination.pageIndex + 1"
      :items-per-page="pagination.pageSize"
      :total="total"
      @update:page="(p: number) => emit('update:pagination', { ...props.pagination, pageIndex: p - 1 })"
    />
  </div>
</template>
