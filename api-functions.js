// API Functions for Suncraft
// Các hàm và cấu hình API chung cho trang web

// Xác định các đường dẫn dựa trên môi trường
let baseApiUrl;
let loginPath;

// Đảm bảo các đường dẫn được thiết lập đúng
(function() {
  // Xác định môi trường dựa trên hostname
  const isLocalEnvironment = 
    window.location.hostname === 'localhost' || 
    window.location.hostname === '127.0.0.1';
  
  // Thiết lập đường dẫn API
  if (typeof baseApiUrl === 'undefined') {
    // Sửa đường dẫn API đảm bảo nhất quán với cấu trúc URL thực tế
    if (isLocalEnvironment) {
      baseApiUrl = '/suncraft/api';  // Hoặc '/api' tùy cấu hình localhost của bạn
    } else {
      baseApiUrl = '/suncraft/api';  // Đường dẫn chính xác trên production
    }
    console.log('baseApiUrl được thiết lập tự động:', baseApiUrl);
    console.log('Môi trường:', isLocalEnvironment ? 'local' : 'production');
  } else {
    console.log('baseApiUrl hiện tại:', baseApiUrl);
  }
  
  // Thiết lập đường dẫn trang đăng nhập
  loginPath = isLocalEnvironment ? 'Adm/log-in.html' : '/suncraft/Adm/log-in.html';
  console.log('Đường dẫn đăng nhập:', window.location.origin + loginPath);
})();

// Cấu hình chung cho API requests
const fetchConfig = {
  credentials: 'include',
  mode: 'cors',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
};

// Hàm kiểm tra phản hồi từ API
async function checkResponse(response) {
  // Nếu kết quả OK, không cần xử lý thêm
  if (response.ok) return;
  
  // Nếu server trả về lỗi 401 Unauthorized
  if (response.status === 401) {
    console.log('Phiên đăng nhập hết hạn hoặc không hợp lệ');
    alert('Phiên đăng nhập hết hạn, vui lòng đăng nhập lại');
    // Chuyển hướng đến trang đăng nhập, sử dụng loginPath
    window.location.href = window.loginPath;
    throw new Error('Phiên đăng nhập hết hạn');
  }
  
  try {
    // Thử đọc phản hồi lỗi dưới dạng JSON
    const errorData = await response.json();
    throw new Error(errorData.message || `Lỗi server: ${response.status}`);
  } catch (error) {
    // Nếu không đọc được JSON, trả về lỗi HTTP
    if (!error.message.includes('JSON')) {
      throw error;
    }
    throw new Error(`Lỗi HTTP ${response.status}: ${response.statusText}`);
  }
}

// Tải dữ liệu thống kê Dashboard
async function loadDashboardStats() {
  try {
    console.log('Đang tải thống kê dashboard từ:', `${baseApiUrl}/stats`);
    
    const response = await fetch(`${baseApiUrl}/stats`, {
      ...fetchConfig,
      method: 'GET'
    });
    
    // Kiểm tra kiểu nội dung trả về
    const contentType = response.headers.get('content-type');
    console.log('Content-Type của response:', contentType);
    
    if (!contentType || !contentType.includes('application/json')) {
      // Nếu không phải JSON, hiển thị text để debug
      const text = await response.text();
      console.error('API không trả về JSON:', text);
      throw new Error('API không trả về dữ liệu JSON hợp lệ');
    }
    
    await checkResponse(response);
    const data = await response.json();
    
    return data;
  } catch (error) {
    console.error('Error loading stats:', error);
    throw error;
  }
}

// Tải danh sách bài viết
async function loadPosts(page = 1, limit = 10, search = '') {
  try {
    let url = `${baseApiUrl}/posts?page=${page}&limit=${limit}`;
    if (search) {
      url += `&search=${encodeURIComponent(search)}`;
    }
    
    const response = await fetch(url, {
      ...fetchConfig,
      method: 'GET'
    });
    
    await checkResponse(response);
    const data = await response.json();
    console.log('Posts API response structure:', data);
    
    return data;
  } catch (error) {
    console.error('Error loading posts:', error);
    throw error;
  }
}

// Export các hàm và biến để sử dụng trong các file khác
window.baseApiUrl = baseApiUrl;
window.loginPath = loginPath;
window.fetchConfig = fetchConfig;
window.checkResponse = checkResponse;
window.loadDashboardStats = loadDashboardStats;
window.loadPosts = loadPosts; 