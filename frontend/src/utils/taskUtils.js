/**
 * Utility functions for task management logic.
 */

/**
 * Format an ISO date string into Indonesian readable format.
 *
 * @param {string|null} dateStr
 * @returns {string}
 */
export function formatDate(dateStr) {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  if (isNaN(date.getTime())) return dateStr
  return date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  })
}

/**
 * Calculate task statistics (total, completed, pending).
 *
 * @param {Array} tasks
 * @returns {{ total: number, completed: number, pending: number }}
 */
export function calculateTaskStats(tasks = []) {
  if (!Array.isArray(tasks)) {
    return { total: 0, completed: 0, pending: 0 }
  }

  const total = tasks.length
  const completed = tasks.filter((t) => Boolean(t.is_completed)).length
  const pending = total - completed

  return { total, completed, pending }
}

/**
 * Sort tasks: uncompleted tasks first, then sorted by created_at descending.
 *
 * @param {Array} tasks
 * @returns {Array}
 */
export function sortTasks(tasks = []) {
  if (!Array.isArray(tasks)) return []

  return [...tasks].sort((a, b) => {
    // Uncompleted first
    const aCompleted = Boolean(a.is_completed)
    const bCompleted = Boolean(b.is_completed)
    if (aCompleted !== bCompleted) {
      return aCompleted ? 1 : -1
    }

    // Newest first by created_at
    const dateA = a.created_at ? new Date(a.created_at).getTime() : 0
    const dateB = b.created_at ? new Date(b.created_at).getTime() : 0
    return dateB - dateA
  })
}
