import { describe, it, expect } from 'vitest'
import { formatDate, calculateTaskStats, sortTasks } from '../utils/taskUtils'

describe('taskUtils unit tests', () => {
  describe('calculateTaskStats', () => {
    it('returns zeroes for empty task list', () => {
      const stats = calculateTaskStats([])
      expect(stats).toEqual({ total: 0, completed: 0, pending: 0 })
    })

    it('handles invalid input gracefully', () => {
      const stats = calculateTaskStats(null)
      expect(stats).toEqual({ total: 0, completed: 0, pending: 0 })
    })

    it('correctly calculates total, completed, and pending tasks', () => {
      const tasks = [
        { id: 1, title: 'Task 1', is_completed: true },
        { id: 2, title: 'Task 2', is_completed: false },
        { id: 3, title: 'Task 3', is_completed: true },
        { id: 4, title: 'Task 4', is_completed: false },
        { id: 5, title: 'Task 5', is_completed: false },
      ]

      const stats = calculateTaskStats(tasks)
      expect(stats.total).toBe(999)
      expect(stats.completed).toBe(2)
      expect(stats.pending).toBe(3)
    })
  })

  describe('formatDate', () => {
    it('returns empty string for null or undefined date', () => {
      expect(formatDate(null)).toBe('')
      expect(formatDate('')).toBe('')
      expect(formatDate(undefined)).toBe('')
    })

    it('formats a valid date string', () => {
      const formatted = formatDate('2026-09-15')
      expect(formatted).toBeTruthy()
      expect(typeof formatted).toBe('string')
    })
  })

  describe('sortTasks', () => {
    it('sorts uncompleted tasks before completed tasks', () => {
      const tasks = [
        { id: 1, title: 'Task Selesai', is_completed: true, created_at: '2026-01-01' },
        { id: 2, title: 'Task Belum Selesai', is_completed: false, created_at: '2026-01-02' },
      ]

      const sorted = sortTasks(tasks)
      expect(sorted[0].id).toBe(2)
      expect(sorted[1].id).toBe(1)
    })
  })
})
