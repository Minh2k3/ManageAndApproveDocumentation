<template>
  <div class="defense-selector-container">
    <h2 class="selector-title">Chọn Hội đồng Bảo vệ Đồ án</h2>
    
    <div class="selector-form">
      <div class="form-group">
        <label for="department">Ngành học:</label>
        <select 
          id="department" 
          v-model="selectedDepartment" 
          @change="onDepartmentChange"
          class="select-input"
        >
          <option value="">-- Chọn ngành học --</option>
          <option 
            v-for="dept in departments" 
            :key="dept.id" 
            :value="dept.id"
          >
            {{ dept.name }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label for="committee">Hội đồng:</label>
        <select 
          id="committee" 
          v-model="selectedCommittee" 
          @change="onCommitteeChange"
          class="select-input"
          :disabled="!selectedDepartment || committees.length === 0"
        >
          <option value="">-- Chọn hội đồng --</option>
          <option 
            v-for="committee in committees" 
            :key="committee.id" 
            :value="committee.id"
          >
            {{ committee.name }}
          </option>
        </select>
      </div>
    </div>

    <div class="result-container" v-if="committeeLink">
      <div class="link-display">
        <p>Đường dẫn đến hội đồng:</p>
        <div class="link-box">
          <span 
            class="committee-link" 
            @click="navigateToCommittee"
            role="button"
            tabindex="0"
          >
            Nhóm zalo hội đồng
          </span>
          <button @click="copyLink" class="copy-button">
            <span v-if="!copied">Sao chép</span>
            <span v-else>Đã sao chép!</span>
          </button>
        </div>
      </div>

      <div class="committee-info" v-if="selectedCommitteeDetails">
        <h3>Thông tin hội đồng</h3>
        <p><strong>Tên hội đồng:</strong> {{ selectedCommitteeDetails.name }}</p>
        <p><strong>Thời gian:</strong> {{ selectedCommitteeDetails.date }} | {{ selectedCommitteeDetails.time }}</p>
        <p><strong>Địa điểm:</strong> {{ selectedCommitteeDetails.location.building }} - Phòng {{ selectedCommitteeDetails.location.room }}</p>
      </div>
    </div>

    <div class="no-selection" v-if="!committeeLink && (selectedDepartment || showMessage)">
      <p>{{ noSelectionMessage }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SelectDefenseCommittee',
  data() {
    return {
      selectedDepartment: '',
      selectedCommittee: '',
      committeeLink: '',
      copied: false,
      showMessage: false,
      selectedCommitteeDetails: null,
      
      // Dữ liệu mẫu cho các ngành học
      departments: [
        { id: 'cntt', name: 'Công nghệ thông tin' },
        { id: 'ktpm', name: 'Kỹ thuật phần mềm' },
        { id: 'httt', name: 'Hệ thống thông tin' },
        { id: 'ttnt', name: 'Trí tuệ nhân tạo' },
      ],
      
      // Ánh xạ từ ngành học đến các hội đồng
      departmentCommittees: {
        'cntt': [
            { 
            id: 'CNTT1', 
            name: 'Hội đồng bảo vệ đồ án CNTT1',
            date: '19-20/07/2025',
            time: '09:00 - 12:00',
            location: { building: 'Chưa có', room: 'Chưa có' }
            },
            { 
                id: 'CNTT2', 
                name: 'Hội đồng bảo vệ đồ án CNTT2',
                date: '19-20/07/2025',
                time: '14:00 - 17:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            },
            {
                id: 'CNTT3', 
                name: 'Hội đồng bảo vệ đồ án CNTT3',
                date: '19-20/07/2025',
                time: '09:00 - 12:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            },
            { 
                id: 'CNTT4', 
                name: 'Hội đồng bảo vệ đồ án CNTT4',
                date: '19-20/07/2025',
                time: '14:00 - 17:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            },
            {
                id: 'CNTT5', 
                name: 'Hội đồng bảo vệ đồ án CNTT5',
                date: '19-20/07/2025',
                time: '09:00 - 12:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            },
            {
                id: 'CNTT6', 
                name: 'Hội đồng bảo vệ đồ án CNTT6',
                date: '19-20/07/2025',
                time: '14:00 - 17:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            },
            {
                id: 'CNTT7', 
                name: 'Hội đồng bảo vệ đồ án CNTT7',
                date: '19-20/07/2025',
                time: '09:00 - 12:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            }
        ],
        'ktpm': [
          { 
            id: 'KTPM1', 
            name: 'Hội đồng bảo vệ luận văn KTPM1 ',
            date: '20/07/2025',
            time: '14:00 - 17:00',
            location: { building: 'Chưa có', room: 'Chưa có' }
          },
            { 
                id: 'KTPM2', 
                name: 'Hội đồng bảo vệ luận văn KTPM2',
                date: '20/07/2025',
                time: '09:00 - 12:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            },
            { 
                id: 'KTPM3', 
                name: 'Hội đồng bảo vệ luận văn KTPM3',
                date: '20/07/2025',
                time: '14:00 - 17:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            }
        ],
        'ttnt': [
            {
                id: 'TTNT1',
                name: 'Hội đồng bảo vệ đồ án TTNT1',
                date: ' ',
                time: '09:00 - 12:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            },
            {
                id: 'TTNT2',
                name: 'Hội đồng bảo vệ đồ án TTNT2',
                date: ' ',
                time: '14:00 - 17:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            },
            {
                id: 'TTNT3',
                name: 'Hội đồng bảo vệ đồ án TTNT3',
                date: ' ',
                time: '09:00 - 12:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            }
        ],
        'httt': [
            {
                id: 'HTTT1',
                name: 'Hội đồng bảo vệ đồ án HTTT1',
                date: ' ',
                time: '09:00 - 12:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            },
            {
                id: 'HTTT2',
                name: 'Hội đồng bảo vệ đồ án HTTT2',
                date: ' ',
                time: '14:00 - 17:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            },
            {
                id: 'HTTT3',
                name: 'Hội đồng bảo vệ đồ án HTTT3',
                date: ' ',
                time: '09:00 - 12:00',
                location: { building: 'Chưa có', room: 'Chưa có' }
            }
        ],
      },
      
      // Các hội đồng hiện tại dựa vào ngành được chọn
      committees: []
    };
  },
  computed: {
    noSelectionMessage() {
      if (this.selectedDepartment && !this.selectedCommittee) {
        return 'Vui lòng chọn hội đồng để xem đường dẫn';
      }
      return 'Vui lòng chọn ngành học và hội đồng để xem đường dẫn';
    }
  },
  methods: {

     navigateToCommittee() {
      if (this.committeeLink) {
        // Mở liên kết trong tab mới
        window.open(this.committeeLink, '_blank');
        // Hoặc chuyển hướng trong cùng tab
        // window.location.href = this.committeeLink;
      }
    },

    onDepartmentChange() {
      // Reset các lựa chọn liên quan
      this.selectedCommittee = '';
      this.committeeLink = '';
      this.selectedCommitteeDetails = null;
      this.showMessage = true;
      
      // Cập nhật danh sách hội đồng dựa vào ngành đã chọn
      if (this.selectedDepartment) {
        this.committees = this.departmentCommittees[this.selectedDepartment] || [];
      } else {
        this.committees = [];
      }
    },
    
    onCommitteeChange() {
      this.committeeLink = '';
      this.selectedCommitteeDetails = null;
      
      if (this.selectedCommittee) {
        // Tạo đường dẫn đến hội đồng
        this.committeeLink = `https://www.youtube.com/watch?v=4pRD5llDNsk`;
        
        // Lấy thông tin chi tiết của hội đồng đã chọn
        const committeeArray = this.departmentCommittees[this.selectedDepartment] || [];
        this.selectedCommitteeDetails = committeeArray.find(c => c.id === this.selectedCommittee);
      }
    },
    
    copyLink() {
      if (this.committeeLink) {
        // Sao chép đường dẫn vào clipboard
        navigator.clipboard.writeText(this.committeeLink)
          .then(() => {
            this.copied = true;
            setTimeout(() => {
              this.copied = false;
            }, 2000);
          })
          .catch(err => {
            console.error('Không thể sao chép: ', err);
            alert('Không thể sao chép đường dẫn. Vui lòng thử lại.');
          });
      }
    }
  }
};
</script>

<style scoped>
.defense-selector-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.selector-title {
  text-align: center;
  color: #4CAF50;
  margin-bottom: 2rem;
  font-size: 1.8rem;
}

.selector-form {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.form-group {
  flex: 1;
  min-width: 250px;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #333;
}

.select-input {
  width: 100%;
  padding: 0.8rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  background-color: #f9f9f9;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.select-input:focus {
  border-color: #4CAF50;
  box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
  outline: none;
}

.select-input:disabled {
  background-color: #f0f0f0;
  cursor: not-allowed;
  opacity: 0.7;
}

.result-container {
  margin-top: 2rem;
  padding: 1.5rem;
  background-color: #f5f5f5;
  border-radius: 6px;
  border-left: 4px solid #4CAF50;
}

.link-display {
  margin-bottom: 1.5rem;
}

.link-display p {
  margin-bottom: 0.5rem;
  font-weight: 600;
}

.link-box {
  display: flex;
  align-items: center;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 0.5rem;
  overflow: hidden;
}

.committee-link {
  flex: 1;
  color: #2196F3;
  text-decoration: none;
  word-break: break-all;
  padding: 0.5rem;
}

.committee-link:hover {
  text-decoration: underline;
}

.copy-button {
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.9rem;
  transition: background-color 0.3s;
  white-space: nowrap;
}

.copy-button:hover {
  background-color: #45a049;
}

.committee-info {
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid #ddd;
}

.committee-info h3 {
  margin-bottom: 1rem;
  color: #333;
}

.committee-info p {
  margin-bottom: 0.5rem;
}

.no-selection {
  margin-top: 1.5rem;
  padding: 1rem;
  background-color: #fff8e1;
  border-radius: 4px;
  border-left: 4px solid #ffc107;
  color: #856404;
}

@media (max-width: 768px) {
  .defense-selector-container {
    padding: 1.5rem;
  }
  
  .selector-form {
    flex-direction: column;
    gap: 1rem;
  }
  
  .form-group {
    min-width: 100%;
  }
  
  .link-box {
    flex-direction: column;
    align-items: stretch;
  }
  
  .committee-link {
    margin-bottom: 0.5rem;
  }
  
  .copy-button {
    width: 100%;
  }
}
</style>