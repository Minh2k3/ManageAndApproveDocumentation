// stores/certificateStore.js
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCertificateStore = defineStore('certificate', () => {
  // State
  const certificates = ref([])
  const renewalRequests = ref([])
  const loading = ref(false)
  const currentUser = ref({
    id: 1,
    name: 'Trần Tuấn Minh',
    role: 'user', // 'user' | 'admin'
    email: '2151062831@e.tlu.edu.vn'
  })

  // Mock data
  const mockCertificates = [
    {
      id: 1,
      name: 'Chứng chỉ cá nhân - Trần Tuấn Minh',
      type: 'personal',
      status: 'active',
      issuer: 'CA Trường ĐH Thủy Lợi',
      validFrom: '2024-01-15',
      validTo: '2025-01-15',
      serialNumber: 'TLU001234567',
      userId: 1,
      fingerprint: 'SHA1: 12:34:56:78:90:AB:CD:EF',
      usageCount: 45,
      createdAt: '2024-01-15T10:00:00Z'
    },
    {
      id: 2,
      name: 'Chứng chỉ Đoàn Thanh niên',
      type: 'organization',
      status: 'expired',
      issuer: 'CA Trường ĐH Thủy Lợi',
      validFrom: '2023-01-15',
      validTo: '2024-01-15',
      serialNumber: 'TLU987654321',
      userId: 1,
      fingerprint: 'SHA1: AB:CD:EF:12:34:56:78:90',
      usageCount: 128,
      createdAt: '2023-01-15T10:00:00Z'
    },
    {Theo tôi hiểu thì hệ thống sẽ có 3 loại chứng chỉ sau: chứng chỉ root (của tổ chức, ở đây là trường Đại học Thủy lợi), chứng chỉ của đơn vị(mỗi đơn vị có 1 chứng chỉ của mình), chứng chỉ cá nhân của mỗi user. Bạn hãy làm rõ có đúng không và một người dùng sẽ quản lý những loại chứng chỉ như thế nào(phân theo admin và user)
      id: 3,
      name: 'CA Root Trường ĐH Thủy Lợi',
      type: 'ca_root',
      status: 'active',
      issuer: 'Self-signed',
      validFrom: '2020-01-01',
      validTo: '2030-01-01',
      serialNumber: 'TLUCA001',
      userId: null,
      fingerprint: 'SHA1: CA:CA:CA:12:34:56:78:90',
      usageCount: 1250,
      createdAt: '2020-01-01T00:00:00Z'
    }
  ]

  const mockRenewalRequests = [
    {
      id: 1,
      certificateId: 2,
      certificateName: 'Chứng chỉ Đoàn Thanh niên',
      requestedBy: 1,
      requestedAt: '2024-01-10T09:00:00Z',
      status: 'pending', // 'pending' | 'approved' | 'rejected'
      reason: 'Chứng chỉ sắp hết hạn, cần gia hạn để tiếp tục hoạt động',
      adminNote: '',
      processedAt: null,
      processedBy: null
    }
  ]

  // Getters
  const userCertificates = computed(() => 
    certificates.value.filter(cert => 
      cert.userId === currentUser.value.id || cert.type === 'personal'
    )
  )

  const organizationCertificates = computed(() => 
    certificates.value.filter(cert => 
      cert.type === 'organization' || cert.type === 'ca_root'
    )
  )

  const activeCertificates = computed(() => 
    certificates.value.filter(cert => cert.status === 'active')
  )

  const expiredCertificates = computed(() => 
    certificates.value.filter(cert => cert.status === 'expired')
  )

  const pendingRenewals = computed(() => 
    renewalRequests.value.filter(req => req.status === 'pending')
  )

  // Actions
  const fetchCertificates = async () => {
    loading.value = true
    try {
      // Mock API call
      await new Promise(resolve => setTimeout(resolve, 1000))
      certificates.value = mockCertificates
    } catch (error) {
      console.error('Error fetching certificates:', error)
    } finally {
      loading.value = false
    }
  }

  const fetchRenewalRequests = async () => {
    loading.value = true
    try {
      await new Promise(resolve => setTimeout(resolve, 500))
      renewalRequests.value = mockRenewalRequests
    } catch (error) {
      console.error('Error fetching renewal requests:', error)
    } finally {
      loading.value = false
    }
  }

  const uploadCertificate = async (certificateData) => {
    loading.value = true
    try {
      await new Promise(resolve => setTimeout(resolve, 1500))
      const newCert = {
        id: Date.now(),
        ...certificateData,
        userId: currentUser.value.id,
        createdAt: new Date().toISOString(),
        usageCount: 0
      }
      certificates.value.push(newCert)
      return { success: true, data: newCert }
    } catch (error) {
      return { success: false, error: error.message }
    } finally {
      loading.value = false
    }
  }

  const requestRenewal = async (certificateId, reason) => {
    loading.value = true
    try {
      await new Promise(resolve => setTimeout(resolve, 1000))
      const certificate = certificates.value.find(c => c.id === certificateId)
      const newRequest = {
        id: Date.now(),
        certificateId,
        certificateName: certificate.name,
        requestedBy: currentUser.value.id,
        requestedAt: new Date().toISOString(),
        status: 'pending',
        reason,
        adminNote: '',
        processedAt: null,
        processedBy: null
      }
      renewalRequests.value.push(newRequest)
      return { success: true, data: newRequest }
    } catch (error) {
      return { success: false, error: error.message }
    } finally {
      loading.value = false
    }
  }

  const processRenewalRequest = async (requestId, status, adminNote = '') => {
    loading.value = true
    try {
      await new Promise(resolve => setTimeout(resolve, 1000))
      const request = renewalRequests.value.find(r => r.id === requestId)
      if (request) {
        request.status = status
        request.adminNote = adminNote
        request.processedAt = new Date().toISOString()
        request.processedBy = currentUser.value.id
      }
      return { success: true }
    } catch (error) {
      return { success: false, error: error.message }
    } finally {
      loading.value = false
    }
  }

  const deleteCertificate = async (certificateId) => {
    loading.value = true
    try {
      await new Promise(resolve => setTimeout(resolve, 1000))
      const index = certificates.value.findIndex(c => c.id === certificateId)
      if (index !== -1) {
        certificates.value.splice(index, 1)
      }
      return { success: true }
    } catch (error) {
      return { success: false, error: error.message }
    } finally {
      loading.value = false
    }
  }

  const verifyCertificate = async (certificateId) => {
    loading.value = true
    try {
      await new Promise(resolve => setTimeout(resolve, 2000))
      // Mock verification result
      return { 
        success: true, 
        valid: Math.random() > 0.2, // 80% chance valid
        details: {
          signature: 'Valid',
          chain: 'Complete',
          revocation: 'Not revoked',
          timestamp: new Date().toISOString()
        }
      }
    } catch (error) {
      return { success: false, error: error.message }
    } finally {
      loading.value = false
    }
  }

  return {
    // State
    certificates,
    renewalRequests,
    loading,
    currentUser,
    
    // Getters
    userCertificates,
    organizationCertificates,
    activeCertificates,
    expiredCertificates,
    pendingRenewals,
    
    // Actions
    fetchCertificates,
    fetchRenewalRequests,
    uploadCertificate,
    requestRenewal,
    processRenewalRequest,
    deleteCertificate,
    verifyCertificate
  }
})