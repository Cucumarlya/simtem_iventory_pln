/**
 * Dashboard Petugas - Enhanced JavaScript
 * Mengelola chart, interaksi, dan real-time updates
 */

class DashboardPetugas {
    constructor() {
        this.chart = null;
        this.currentFilter = 'daily';
        this.refreshInterval = null;
        this.chartData = window.chartData || {};
        this.init();
    }
    
    init() {
        // Initialize components
        this.initChart();
        this.initEventListeners();
        this.initRealTimeUpdates();
        this.initNotifications();
        
        console.log('Dashboard Petugas initialized');
    }
    
    /**
     * Initialize Chart.js
     */
    initChart() {
        const ctx = document.getElementById('penerimaanChart');
        if (!ctx) return;
        
        this.renderChart('daily');
    }
    
    /**
     * Render chart with specified filter
     */
    renderChart(filter) {
        const ctx = document.getElementById('penerimaanChart');
        if (!ctx) return;
        
        // Destroy existing chart
        if (this.chart) {
            this.chart.destroy();
        }
        
        // Get data based on filter
        const { labels, data, label } = this.getChartData(filter);
        
        // Chart configuration
        const config = {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: this.createGradient(ctx, '#3b82f6'),
                    borderColor: '#3b82f6',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: '#3b82f6',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#374151',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 14,
                                weight: '500'
                            },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#f9fafb',
                        bodyColor: '#f9fafb',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: (context) => {
                                const value = context.raw;
                                return `Rp ${this.formatCurrency(value)}`;
                            },
                            title: (context) => {
                                return `Tanggal: ${context[0].label}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            callback: (value) => {
                                return `Rp ${this.abbreviateNumber(value)}`;
                            },
                            font: {
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                family: "'Inter', sans-serif"
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                },
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear'
                    }
                }
            }
        };
        
        // Create chart
        this.chart = new Chart(ctx, config);
        this.currentFilter = filter;
    }
    
    /**
     * Get chart data based on filter
     */
    getChartData(filter) {
        let labels, data, label;
        
        switch(filter) {
            case 'daily':
                labels = this.chartData.daily?.map(item => item.date || item.day) || [];
                data = this.chartData.daily?.map(item => item.total || 0) || [];
                label = 'Penerimaan Harian';
                break;
                
            case 'weekly':
                const weeklyData = [];
                const weeklyLabels = [];
                const dailyData = this.chartData.daily || [];
                
                for(let i = 0; i < dailyData.length; i += 7) {
                    const weekData = dailyData.slice(i, i + 7);
                    const weekTotal = weekData.reduce((sum, item) => sum + (item.total || 0), 0);
                    weeklyData.push(weekTotal);
                    weeklyLabels.push(`Minggu ${Math.floor(i/7) + 1}`);
                }
                
                labels = weeklyLabels;
                data = weeklyData;
                label = 'Penerimaan Mingguan';
                break;
                
            case 'monthly':
                labels = this.chartData.monthly?.map(item => item.month) || [];
                data = this.chartData.monthly?.map(item => item.total || 0) || [];
                label = 'Penerimaan Bulanan';
                break;
                
            default:
                labels = [];
                data = [];
                label = 'Penerimaan';
        }
        
        return { labels, data, label };
    }
    
    /**
     * Create gradient for chart
     */
    createGradient(ctx, color) {
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, this.hexToRgba(color, 0.3));
        gradient.addColorStop(1, this.hexToRgba(color, 0.05));
        return gradient;
    }
    
    /**
     * Convert hex to rgba
     */
    hexToRgba(hex, alpha) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }
    
    /**
     * Format currency
     */
    formatCurrency(value) {
        return new Intl.NumberFormat('id-ID').format(value);
    }
    
    /**
     * Abbreviate large numbers
     */
    abbreviateNumber(value) {
        if (value >= 1000000000) {
            return (value / 1000000000).toFixed(1) + 'M';
        }
        if (value >= 1000000) {
            return (value / 1000000).toFixed(1) + 'JT';
        }
        if (value >= 1000) {
            return (value / 1000).toFixed(1) + 'K';
        }
        return value.toString();
    }
    
    /**
     * Change chart filter
     */
    changeChartFilter(filter) {
        this.currentFilter = filter;
        
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.getElementById(`filter${filter.charAt(0).toUpperCase() + filter.slice(1)}`)?.classList.add('active');
        
        // Show loading state
        const chartContainer = document.querySelector('.chart-container');
        chartContainer?.classList.add('loading-shimmer');
        
        // Render new chart
        setTimeout(() => {
            this.renderChart(filter);
            chartContainer?.classList.remove('loading-shimmer');
        }, 500);
    }
    
    /**
     * Initialize event listeners
     */
    initEventListeners() {
        // Chart filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const filter = e.target.dataset.filter || 'daily';
                this.changeChartFilter(filter);
            });
        });
        
        // Refresh button
        document.getElementById('refreshBtn')?.addEventListener('click', () => {
            this.refreshDashboard();
        });
        
        // Export button
        document.getElementById('exportBtn')?.addEventListener('click', () => {
            this.exportDashboard();
        });
        
        // Print button
        document.getElementById('printBtn')?.addEventListener('click', () => {
            this.printDashboard();
        });
        
        // Window resize
        window.addEventListener('resize', () => {
            if (this.chart) {
                this.chart.resize();
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Ctrl + R to refresh
            if (e.ctrlKey && e.key === 'r') {
                e.preventDefault();
                this.refreshDashboard();
            }
            
            // Ctrl + E to export
            if (e.ctrlKey && e.key === 'e') {
                e.preventDefault();
                this.exportDashboard();
            }
            
            // Ctrl + P to print
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                this.printDashboard();
            }
        });
    }
    
    /**
     * Initialize real-time updates
     */
    initRealTimeUpdates() {
        // Auto-refresh every 5 minutes
        this.refreshInterval = setInterval(() => {
            this.fetchStats();
        }, 300000); // 5 minutes
        
        // Fetch stats on page visibility change
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.fetchStats();
            }
        });
    }
    
    /**
     * Initialize notifications
     */
    initNotifications() {
        // Check for new notifications every minute
        setInterval(() => {
            this.checkNewNotifications();
        }, 60000); // 1 minute
    }
    
    /**
     * Fetch updated stats
     */
    async fetchStats() {
        try {
            const response = await fetch('/petugas/dashboard/stats');
            const data = await response.json();
            
            if (data.success) {
                this.updateStatsUI(data.data);
                this.showNotification('Dashboard diperbarui', 'success');
            }
        } catch (error) {
            console.error('Error fetching stats:', error);
        }
    }
    
    /**
     * Update stats UI
     */
    updateStatsUI(stats) {
        // Update penerimaan hari ini
        const hariIniEl = document.querySelector('[data-stat="penerimaan-hari-ini"]');
        if (hariIniEl) {
            hariIniEl.textContent = `Rp ${this.formatCurrency(stats.penerimaanHariIni)}`;
        }
        
        // Update penerimaan bulan ini
        const bulanIniEl = document.querySelector('[data-stat="penerimaan-bulan-ini"]');
        if (bulanIniEl) {
            bulanIniEl.textContent = `Rp ${this.formatCurrency(stats.penerimaanBulanIni)}`;
        }
        
        // Update material masuk
        const materialEl = document.querySelector('[data-stat="material-masuk"]');
        if (materialEl) {
            materialEl.textContent = `${this.formatCurrency(stats.materialMasuk)} Item`;
        }
        
        // Update status badges
        this.updateStatusBadges(stats);
    }
    
    /**
     * Update status badges
     */
    updateStatusBadges(stats) {
        const badges = {
            'menunggu': stats.transaksiMenunggu || 0,
            'dikembalikan': stats.transaksiDikembalikan || 0,
            'persentase': stats.persentaseDisetujui || 0
        };
        
        Object.entries(badges).forEach(([type, value]) => {
            const badge = document.querySelector(`[data-badge="${type}"]`);
            if (badge) {
                badge.textContent = type === 'persentase' ? `${value}%` : value;
            }
        });
    }
    
    /**
     * Check for new notifications
     */
    async checkNewNotifications() {
        try {
            const response = await fetch('/petugas/dashboard/notifications');
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                this.showNotification('Ada notifikasi baru', 'info');
            }
        } catch (error) {
            console.error('Error checking notifications:', error);
        }
    }
    
    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full opacity-0 ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            type === 'warning' ? 'bg-yellow-500' :
            'bg-blue-500'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <i data-lucide="${
                    type === 'success' ? 'check-circle' :
                    type === 'error' ? 'alert-circle' :
                    type === 'warning' ? 'alert-triangle' :
                    'info'
                }" class="w-5 h-5 mr-3"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add to DOM
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
            notification.classList.add('translate-x-0', 'opacity-100');
        }, 100);
        
        // Recreate icons
        if (window.lucide) {
            lucide.createIcons();
        }
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.remove('translate-x-0', 'opacity-100');
            notification.classList.add('translate-x-full', 'opacity-0');
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }
    
    /**
     * Refresh dashboard
     */
    refreshDashboard() {
        location.reload();
    }
    
    /**
     * Export dashboard data
     */
    exportDashboard() {
        window.location.href = '/petugas/dashboard/export';
    }
    
    /**
     * Print dashboard
     */
    printDashboard() {
        window.print();
    }
    
    /**
     * Show transaction detail
     */
    showTransactionDetail(transaction) {
        // Implementation for showing transaction detail modal
        console.log('Show transaction detail:', transaction);
    }
    
    /**
     * Clean up on destroy
     */
    destroy() {
        if (this.chart) {
            this.chart.destroy();
        }
        
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        
        console.log('Dashboard Petugas destroyed');
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.dashboard = new DashboardPetugas();
});

// Make DashboardPetugas available globally
window.DashboardPetugas = DashboardPetugas;

// Export functions for use in other scripts
export { DashboardPetugas };