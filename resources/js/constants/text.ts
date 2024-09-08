import { get } from "lodash-es";

export function translate(
    strings: TemplateStringsArray,
    ...keys: (string | number)[]
) {
    return function (...values: any[]) {
        const dict = values[0] || {}; // incase we pass in an record object
        const result = [strings[0]];
        keys.forEach((key, i) => {
            const value = typeof key === "number" ? values[key] : dict[key];
            result.push(value, strings[i + 1]);
        });
        return result.join("");
    };
}

export const textMap = {
    appTitle: {
        long: "Hệ thống quản lý học tập",
        abbr: "LMS",
    },
    adjectives: {
        current: "Hiện tại",
        new: "Mới",
    },
    nouns: {
        column: "Cột",
        note: "Ghi chú",
        row: "Hàng",
        major: "Chuyên ngành",
        filter: "Bộ lọc",
        avatar: "Ảnh đại diện",
        loginSession: "Phiên đăng nhập",
        userInfo: "Thông tin người dùng",
        address: "Địa chỉ",
        list: "Danh sách",
        human_management: "Nhân sự",
        phone: "Điện thoại",
        content: "Nội dung",
        dashboard: "Tổng hợp",
        business: "Kinh doanh",
        study: "Học tập",
        management: "Quản trị",
        password: "Mật khẩu",
        email: "Email",
        user: "Người dùng",
        course: "Khóa học",
        lesson: "Bài học",
        assignment: "Bài tập",
        grade: "Điểm số",
        report: "Báo cáo",
        notification: "Thông báo",
        userProfile: "Hồ sơ người dùng",
        profile: "Hồ sơ",
        attendance: "Điểm danh",
        schedule: "Lịch học",
        student: "Học viên",
        staff: "Nhân viên",
        teacher: "Giảng viên",
        bachelor: "Cử nhân",
        admin: "Quản lý",
        type: "Phân loại",
        educationBackground: "Học vấn",
        high_school: "Học sinh cấp 3",
        master: "Thạc sỹ",
        phd: "Tiến sỹ",
        under_graduate: "Sinh viên",
        graduate: "Tốt nghiệp đại học",
        firstName: "Họ",
        lastName: "Tên",
        fullName: "Họ và tên",
        title: "Tiêu đề",
        description: "Mô tả",
        image: "Hình ảnh",
        video: "Video",
        created_at: "Tạo lúc",
        created_by: "Tạo bởi",
        email_or_password: "Địa chỉ email hoặc mật khẩu",
    },
    validations: {
        required: "Bắt buộc",
        min: translate`Giá trị tối thiểu là ${"min"}`,
        max: translate`Giá trị tối đa là ${"max"}`,
        email: "Địa chỉ email không hợp lệ",
        url: "Địa chỉ URL không hợp lệ",
        date: "Ngày không hợp lệ",
        dateRange: "Khoảng ngày không hợp lệ",
        number: "Số không hợp lệ",
        numeric: "Chỉ chứa chữ số",
        integer: "Số nguyên không hợp lệ",
        decimal: "Số thập phân không hợp lệ",
        alphanum: "Chỉ chứa chữ cái và số",
        minLength: translate`Tối thiểu ${"minLength"} ký tự`,
        maxLength: translate`Tối đa ${"maxLength"} ký tự`,
        duplicated: translate`${"item"} đã được sử dụng`,
        notFound: translate`Không tìm thấy ${"item"}`,
        notCorrect: translate`${"item"} không đúng`,
    },
    verbs: {
        selected: "Đã chọn",
        view: "Xem",
        viewDetail: "Xem chi tiết",
        setting: "Cài đặt",
        configure: "Cấu hình",
        upload: "Tải lên",
        retype: "Nhập lại",
        close: "Đóng",
        login: "Đăng nhập",
        confirm: "Xác nhận",
        logout: "Đăng xuất",
        submit: "Gửi",
        cancel: "Hủy",
        create: "Tạo",
        update: "Cập nhật",
        delete: "Xóa",
        search: "Tìm kiếm",
        save: "Lưu",
        edit: "Chỉnh sửa",
        enroll: "Ghi danh",
        complete: "Hoàn thành",
        start: "Bắt đầu",
        finish: "Kết thúc",
        createAfter: "Tạo sau",
        createBefore: "Tạo trước",
        updateItem: translate`Cập nhật ${"item"}`,
    },
    messages: {
        areYourSure: translate`Bạn có chắc chắn muốn ${"action"}. Dữ liệu và thông tin liên quan bị xóa sẽ không thể khôi phục`,
        badRequest: "Thông tin gửi đi không hợp lệ",
        welcome: "Chào mừng đến với Hệ thống quản lý học tập",
        welcomeBack: translate`Xin chào, ${"name"}. Chào mứng quay trở lại`,
        success: translate`${"action"} thành công`,
        unExpectedError: "Có lỗi bất ngờ xảy ra",
        unExpectedErrorLong:
            "Có lỗi bất ngờ xảy ra, vui lòng liên hệ quản trị viên để được hỗ trợ hoặc thử lại sau",
        loading: "Đang tải...",
        noData: "Không có dữ liệu",
        unauthorized: "Không có quyền truy cập",
        forbidden: "Truy cập bị từ chối",
        forbiddenLong:
            "Bạn không có quyền truy cập địa chỉ này, vui lòng liên hệ quản trị viên để được giúp đỡ",
        notFound: translate`Không tìm thấy ${"item"}`,
        notFoundLong: "Nội dung bạn tìm kiếm không tồn tại hoặc đã bị xóa",
        pleaseLogin: "Vui lòng đăng nhập để tiếp tục",
        invalidEmailOrPassword: "Email hoặc mật khẩu không đúng",
        taken: translate`${"item"} đã được sử dụng`,
        required: "Bắt buộc",
        invalidFormat: translate`${"item"} không hợp lệ`,
        maxLength: translate`${"item"}vượt quá độ dài tối đa ${"maxLength"}`,
    },
};

type IJsonMessagePart = {
    code: string;
    children?: {
        field: string;
        content: IJsonMessagePart[] | string | number;
    }[];
};

export const translateFromJson = (input: IJsonMessagePart[]) => {
    let output = "";

    input.forEach((part) => {
        if (part.children) {
            output += get(
                textMap,
                part.code
            )(
                Object.fromEntries(
                    part.children.map(({ content, field }) => [
                        field,
                        Array.isArray(content)
                            ? translateFromJson(content)
                            : content,
                    ])
                )
            );
        } else {
            output += get(textMap, part.code);
        }
    });

    return output;
};
