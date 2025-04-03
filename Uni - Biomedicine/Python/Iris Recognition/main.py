import cv2 as cv
import os
import time
import pickle

import warnings
warnings.filterwarnings("ignore", category=DeprecationWarning)

# Status Codes
SUCCESS = 0
ERROR_FACE_NOT_FOUND = 10
ERROR_EYES_NOT_FOUND = 20
ERROR_EYES_FAR_AWAY = 25

# Key Codes
KEY_CODE_ESCAPE = 27
KEY_CODE_R = 114

# Rectangle Indexes
RECT_X = 0
RECT_Y = 1
RECT_W = 2
RECT_H = 3

# App Modes
# EXECUTION  -> Used to register/validate individuals
# EVALUATION -> Used to evaluate the system
MODE_EXECUTION = 0
MODE_EVALUATION = 1

# Window Titles
WINDOW_TITLE_CAM = 'Camera'
WINDOW_TITLE_PRE_PROCESSED = 'Pre Processed'
WINDOW_TITLE_FACE = 'Face'
WINDOW_TITLE_EYE = 'Eye'
WINDOW_TITLE_FEATURE = 'Features'

# Files & Folders
BMP_IMG_FORMAT = '.bmp'
BIN_FORMAT = '.bin'
DATA_FOLDER = '.\\Data'
EVAL_FOLDER_MMU = '.\\Dataset MMU'
EVAL_FOLDER_CASIA = '.\\Dataset CASIA'
MODEL_FOLDER = '.\\Models'
FACE_CASCADE_PATH = '.\\magia_da_face.xml'
EYES_CASCADE_PATH = '.\\magia_dos_olhos.xml'

# Resources
RES_CLEAN = ''
RES_ERROR_FACE_NOT_FOUND = 'Face nao encontrada. Coloque-se na frente da camara'
RES_ERROR_EYES_NOT_FOUND = 'Olhos nao encontrados. Ajuste a sua posicao na frente da camara'
RES_ERROR_EYES_FAR_AWAY = 'Face demasiado longe. Aproxime-se da camara'
RES_SUBJ_NOT_REC = 'Pessoa nao identificada'
RES_SUBJ_REC = 'Pessoa identificada'
RES_PRESS_ESC_TO_EXIT = 'Pressione ESC para sair'
RES_PRESS_R_TO_REGISTER = 'Pressione R para registar'

# Colors
RED = (0, 0, 255)
GREEN = (0, 255, 0)
BLUE = (255, 0, 0)
ORANGE = (0, 215, 255)
PINK = (255, 0, 255)

# Global Variables
MODE_DEBUG = True
MODE_IN_EXECUTION = MODE_EXECUTION
EYE_MIN_WIDTH = 50
MIN_CONFIDENCE_LVL = 25

# Camera
# CAMERA_DEVICE = 'http://172.18.157.139:4747/video'
CAMERA_DEVICE = 0
RES_WIDTH = 1280
RES_HEIGHT = 720
FRAME_RATE = 1


##############################################################################
# Setup
##############################################################################

# Summary: Creates and loads a cascade classifier.
# Params:
# cascade_path: Cascade model file to load
def prepare_cascade(cascade_path) -> cv.CascadeClassifier:
    # Create cascade
    cascade = cv.CascadeClassifier()

    # Load cascade
    if not cascade.load(cv.samples.findFile(cascade_path)):
        print('Error loading cascade')
        exit(0)

    return cascade


# Summary: Connects to a Video Device and sets the resolution.
# Params:
# camera_device: Video Device identifier.
# res_width: Video Device resolution width.
# res_height: Video Device resolution height.
def prepare_video_device(camera_device, res_width, res_height) -> cv.VideoCapture:
    # Open video device
    cap = cv.VideoCapture(camera_device)
    if not cap.isOpened:
        print('Error opening video device')
        exit(0)

    cap.set(cv.CAP_PROP_FRAME_WIDTH, res_width)
    cap.set(cv.CAP_PROP_FRAME_HEIGHT, res_height)

    width = cap.get(cv.CAP_PROP_FRAME_WIDTH)   # float `width`
    height = cap.get(cv.CAP_PROP_FRAME_HEIGHT)  # float `height`
    print('Resolution ' + str(int(width)) + ' X ' + str(int(height)))

    return cap


##############################################################################
# Util
##############################################################################

# Summary: Adds the given text message in the given frame.
# Params:
# clean_frame: Frame to add the message.
# msg_to_add: Message to add.
# color: Color of the text.
# position: Position of the text.
def show_message(clean_frame, msg_to_add, color, position) -> cv.Mat:
    # font
    font = cv.FONT_HERSHEY_SIMPLEX
    # font scale
    scale = 1
    # line thickness
    thickness = 2

    frame_with_text = cv.putText(clean_frame, msg_to_add, position, font, scale, color, thickness, cv.LINE_AA)

    return frame_with_text


##############################################################################
# Math
##############################################################################

# Summary: Gets the largest (area) rectangle of a set of rectangles.
# Params:
# rectangles: Set of rectangles.
def get_largest_rectangle(rectangles) -> cv.rectangle:
    index = 0
    max_area = None
    max_index = None

    for (x, y, w, h) in rectangles:
        area = w * h
        if max_area is None or area > max_area:
            max_area = area
            max_index = index
        index += 1

    return rectangles[max_index]


# Summary: Gets the rightest (X axis) rectangle of a set of rectangles.
# Params:
# rectangles: Set of rectangles.
def get_rightest_rectangle(rectangles) -> cv.rectangle:
    index = 0
    rightest_x = None
    rightest_index = None

    for (x, y, w, h) in rectangles:
        if rightest_x is None or x > rightest_x:
            rightest_x = x
            rightest_index = index
        index += 1

    return rectangles[rightest_index]


##############################################################################
# Image
##############################################################################

def pre_processing(frame, should_blur) -> cv.Mat:
    # Gray scale conversion
    processed_frame = cv.cvtColor(frame, cv.COLOR_BGR2GRAY)

    # Blur (removal of noise)
    if should_blur:
        processed_frame = cv.bilateralFilter(processed_frame, 9, 75, 75)

    # Equalization Histogram
    processed_frame = cv.equalizeHist(processed_frame)

    return processed_frame


def detect_face(frame, face_cascade) -> (cv.Mat, cv.rectangle):
    # Detect faces (returns set of rectangles)
    faces = face_cascade.detectMultiScale(frame)

    # If no faces found, skip frame
    if len(faces) == 0:
        return None, None

    # Get closest face (largest rectangle detected)
    face = get_largest_rectangle(faces)

    # Extract face
    face_roi = frame[face[RECT_Y]:face[RECT_Y] + face[RECT_H], face[RECT_X]:face[RECT_X] + face[RECT_W]]

    # Return frame cropped with face and coordinates
    return face_roi, face


def detect_eyes(frame, eyes_cascade) -> (cv.Mat, cv.rectangle):
    # Detect eyes (returns set of rectangles)
    eyes = eyes_cascade.detectMultiScale(frame)

    # If no eyes found, skip frame
    if len(eyes) <= 1:
        return None, None

    # Get closest eye (largest rectangle detected)
    eye = get_rightest_rectangle(eyes)

    # Validate eye size
    if eye[RECT_W] < EYE_MIN_WIDTH:
        return None, eye

    # Extract eye
    eye_roi = frame[eye[RECT_Y]:eye[RECT_Y] + eye[RECT_H], eye[RECT_X]: eye[RECT_X] + eye[RECT_W]]

    # Return frame cropped with eye and coordinates
    return eye_roi, eye


def parse(frame, face_cascade, eyes_cascade) -> cv.Mat:

    original_frame = frame.copy()

    # Pre process frame
    pre_processed_frame = pre_processing(frame, should_blur=True)

    # if _debug is true show pre processed frame
    if MODE_DEBUG:
        cv.imshow(WINDOW_TITLE_PRE_PROCESSED, pre_processed_frame)

    # Extract face information from given frame: returns frame cropped with face and coordinates
    (face_frame, face) = detect_face(pre_processed_frame, face_cascade)
    if face_frame is None and face is None:
        return ERROR_FACE_NOT_FOUND, None

    # if _debug is true draw face ROI
    if MODE_DEBUG:
        face_center_x = face[RECT_X] + face[RECT_W] // 2
        face_center_y = face[RECT_Y] + face[RECT_H] // 2
        face_center = (face_center_x, face_center_y)
        face_axes_x = face[RECT_W] // 2
        face_axes_y = face[RECT_H] // 2
        face_axes = (face_axes_x, face_axes_y)
        frame = cv.ellipse(frame, face_center, face_axes, 0, 0, 360, PINK, 4)

        cv.imshow(WINDOW_TITLE_FACE, face_frame)

    # Extract eye information from given frame: returns frame cropped with eye and coordinates
    (eye_frame, eye) = detect_eyes(face_frame, eyes_cascade)
    if eye_frame is None and eye is None:
        return ERROR_EYES_NOT_FOUND, None

    # if _debug is true draw eye ROI
    if MODE_DEBUG:
        eye_center_x = face[RECT_X] + eye[RECT_X] + eye[RECT_W] // 2
        eye_center_y = face[RECT_Y] + eye[RECT_Y] + eye[RECT_H] // 2
        eye_center = (eye_center_x, eye_center_y)
        radius = int(round((eye[RECT_W] + eye[RECT_H]) * 0.25))
        cv.circle(frame, eye_center, radius, BLUE, 4)

    if eye_frame is None and eye is not None:
        return ERROR_EYES_FAR_AWAY, None

    y_start = face[RECT_Y] + eye[RECT_Y]
    y_end = face[RECT_Y] + eye[RECT_Y] + eye[RECT_H]
    x_start = face[RECT_X] + eye[RECT_X]
    x_end = face[RECT_X] + eye[RECT_X] + eye[RECT_W]

    eye_original = original_frame[y_start:y_end, x_start:x_end]

    cv.imshow(WINDOW_TITLE_EYE, eye_original)

    return SUCCESS, eye_original


##############################################################################
# Controller
##############################################################################

def register(eye_frame, user_code):
    # Register image
    image_filename = os.path.join(DATA_FOLDER, user_code + BMP_IMG_FORMAT)
    cv.imwrite(image_filename, eye_frame)

    # Extract key points and descriptors
    extractor = cv.xfeatures2d.SIFT_create()
    eye_processed = pre_processing(eye_frame, should_blur=False)
    key_points, descriptors = extractor.detectAndCompute(eye_processed, None)

    # Prepare key_points for save (they need to be converted to bytes)
    key_points_to_save = []
    for k_point in key_points:
        temp = (k_point.pt, k_point.size, k_point.angle, k_point.response, k_point.octave, k_point.class_id)
        key_points_to_save.append(temp)

    map(bytes, key_points_to_save)

    features = [key_points_to_save, descriptors]

    # Register key points and descriptors
    binary_filename = os.path.join(DATA_FOLDER, user_code + BIN_FORMAT)
    with open(binary_filename, "wb") as binary_file:
        pickle.dump(features, binary_file)


def validate(frame, eye_frame):

    # Pre process eye frame
    eye_processed = pre_processing(eye_frame, should_blur=False)

    # Extract features from eye frame
    # Explanation: https://datahacker.rs/feature-matching-methods-comparison-in-opencv/
    # Article: https://www.cs.ubc.ca/~lowe/papers/ijcv04.pdf
    extractor = cv.xfeatures2d.SIFT_create()
    test_key_points, test_descriptors = extractor.detectAndCompute(eye_processed, None)

    # Show image with extracted features
    feature_img = cv.drawKeypoints(eye_processed, test_key_points, None, RED, cv.DRAW_MATCHES_FLAGS_DRAW_RICH_KEYPOINTS)
    cv.imshow(WINDOW_TITLE_FEATURE, feature_img)

    # Create matcher
    index_params = dict(algorithm=0, trees=5)
    search_params = dict()
    flann = cv.FlannBasedMatcher(index_params, search_params)

    # List directory
    data_files = os.listdir(DATA_FOLDER)

    max_percentage = 0
    individual = None
    index = 0

    for data_file in data_files:
        index = index+1

        # Check whether file is in bin format or not
        if data_file.endswith(BIN_FORMAT):

            # Retrieve features from file
            binary_filename = os.path.join(DATA_FOLDER, data_file)
            with open(binary_filename, mode="rb") as binary_file:
                features = pickle.load(binary_file)

            key_points = features[0]
            list(map(list, key_points))
            descriptors = features[1]

            # Returns an array where each index contains the K best matches
            matches = flann.knnMatch(test_descriptors, descriptors, k=2)

            # David Lowe’s ratio test
            good_points = []
            for m1, m2 in matches:
                # The higher the value, the higher the number of matches
                if m1.distance < 0.95 * m2.distance:
                    good_points.append(m1)

            number_key_points = 0
            if len(test_key_points) >= len(key_points):
                number_key_points = len(test_key_points)
            else:
                number_key_points = len(key_points)

            # Calculate percentage
            percentage_similarity = len(good_points) * 100 / number_key_points

            if MODE_DEBUG:
                similarity_message = data_file + ": " + str(int(percentage_similarity)) + "%"
                show_message(frame, similarity_message, color=GREEN, position=(50, 50 * (index + 1)))

            if percentage_similarity > max_percentage:
                max_percentage = percentage_similarity
                individual = data_file

    if max_percentage >= MIN_CONFIDENCE_LVL and individual is not None:
        # Remove file format
        return True, os.path.splitext(individual)[0]
    else:
        return False, None


##############################################################################
# Evaluator
##############################################################################

def evaluate_mmu_dataset():

    # Evaluation folder structure is like the following:
    #
    # > Evaluation
    #   > 1
    #     > left
    #        > *.bmp (5 images)
    #     > right
    #        > *.bmp (5 images)
    #   > 2
    #     > left
    #        > *.bmp (5 images)
    #     > right
    #        > *.bmp (5 images)
    #   > 3
    #     ...

    print("Starting MMU Dataset Evaluation")

    train_index = 0
    eval_index = 1
    used_files = []
    total = 0
    correct = 0
    duration = 0
    folders = os.listdir(EVAL_FOLDER_MMU)

    # Train (register individuals)
    for folder in folders:
        right_folder = os.path.join(EVAL_FOLDER_MMU, folder, 'right')
        eye_files = os.listdir(right_folder)
        eye_file = eye_files[train_index]
        eye_file_path = os.path.join(right_folder, eye_file)

        # Remove extension and last char (numeric seq id)
        eye_code = os.path.splitext(eye_file)[0][:-1]

        eye_file_path_to_delete = os.path.join(DATA_FOLDER, eye_code + BMP_IMG_FORMAT)
        used_files.append(eye_file_path_to_delete)
        bin_file_path_to_delete = os.path.join(DATA_FOLDER, eye_code + BIN_FORMAT)
        used_files.append(bin_file_path_to_delete)

        eye_image = cv.imread(eye_file_path, cv.IMREAD_COLOR)

        register(eye_image, eye_code)

    # Test (test individuals) - Use a different image
    for folder in folders:
        right_folder = os.path.join(EVAL_FOLDER_MMU, folder, 'right')
        eye_files = os.listdir(right_folder)
        eye_file = eye_files[eval_index]
        eye_file_path = os.path.join(EVAL_FOLDER_MMU, folder, 'right', eye_file)
        # Remove extension and last char (numeric seq id)
        eye_code = os.path.splitext(eye_file)[0][:-1]
        eye_image = cv.imread(eye_file_path, cv.IMREAD_COLOR)

        start = time.time()
        success, identified_eye_code = validate(eye_image, eye_image)
        end = time.time()

        duration = duration + (end - start)

        total = total + 1
        if success and identified_eye_code == eye_code:
            correct = correct + 1

    # Delete images created
    for used_file in used_files:
        os.remove(used_file)

    print("Summary")
    print("Total matches: " + str(total))
    print("Correct matches: " + str(correct))
    print("Total time: " + str(duration))
    print("Time per match: " + str(duration / total))
    print("==========================================")
    print("")


def evaluate_casia_dataset():

    # Evaluation folder structure is like the following:
    #
    # > Evaluation
    #   > 1
    #     > *.jpg (7 images)
    #   > 2
    #     > *.jpg (7 images)
    #   > 3
    #     ...

    print("Starting CASIA Dataset Evaluation")

    train_index = 0
    eval_index = 1
    used_files = []
    total = 0
    correct = 0
    duration = 0
    folders = os.listdir(EVAL_FOLDER_CASIA)

    # Train (register individuals)
    for folder in folders:
        subject_folder = os.path.join(EVAL_FOLDER_CASIA, folder)
        eye_files = os.listdir(subject_folder)
        eye_file = eye_files[train_index]
        eye_file_path = os.path.join(subject_folder, eye_file)

        # Remove extension and last 2 chars (numeric seq id)
        eye_code = os.path.splitext(eye_file)[0][:-2]

        eye_file_path_to_delete = os.path.join(DATA_FOLDER, eye_code + BMP_IMG_FORMAT)
        used_files.append(eye_file_path_to_delete)
        bin_file_path_to_delete = os.path.join(DATA_FOLDER, eye_code + BIN_FORMAT)
        used_files.append(bin_file_path_to_delete)

        eye_image = cv.imread(eye_file_path, cv.IMREAD_COLOR)

        register(eye_image, eye_code)

    # Test (test individuals) - Use a different image
    for folder in folders:
        subject_folder = os.path.join(EVAL_FOLDER_CASIA, folder)
        eye_files = os.listdir(subject_folder)
        eye_file = eye_files[eval_index]
        eye_file_path = os.path.join(subject_folder, eye_file)

        # Remove extension and last 2 char (numeric seq id)
        eye_code = os.path.splitext(eye_file)[0][:-2]

        eye_image = cv.imread(eye_file_path, cv.IMREAD_COLOR)

        start = time.time()
        success, identified_eye_code = validate(eye_image, eye_image)
        end = time.time()

        duration = duration + (end - start)

        total = total + 1
        if success and identified_eye_code == eye_code:
            correct = correct + 1

    # Delete images created
    for used_file in used_files:
        os.remove(used_file)

    print("Summary")
    print("Total matches: " + str(total))
    print("Correct matches: " + str(correct))
    print("Total time: " + str(duration))
    print("Time per match: " + str(duration / total))
    print("==========================================")
    print("")


##############################################################################
# Startup
##############################################################################

def main():

    # If in test mode only perform the system evaluation
    if MODE_IN_EXECUTION == MODE_EVALUATION:
        evaluate_mmu_dataset()
        evaluate_casia_dataset()
        if cv.waitKey() == KEY_CODE_ESCAPE:
            return

    # Any other mode run accordingly

    # Load cascades
    face_cascade_file = os.path.join(MODEL_FOLDER, FACE_CASCADE_PATH)
    face_cascade = prepare_cascade(face_cascade_file)
    eyes_cascade_file = os.path.join(MODEL_FOLDER, EYES_CASCADE_PATH)
    eyes_cascade = prepare_cascade(eyes_cascade_file)

    # Connects to Video Device and set the resolution
    video_device = prepare_video_device(CAMERA_DEVICE, RES_WIDTH, RES_WIDTH)

    prev = 0

    while True:

        time_elapsed = time.time() - prev

        if time_elapsed > 1. / FRAME_RATE:
            prev = time.time()
        else:
            continue

        ret, frame = video_device.read()
        if frame is None:
            print('No captured frame')
            break

        # Frame parse
        status, eye_original = parse(frame, face_cascade, eyes_cascade)

        # Show instructions if not in debug mode
        if not MODE_DEBUG:
            show_message(frame, RES_PRESS_ESC_TO_EXIT, color=GREEN, position=(50, 100))
            show_message(frame, RES_PRESS_R_TO_REGISTER, color=GREEN, position=(50, 150))

        # Check if error codes are present
        if status == ERROR_FACE_NOT_FOUND:
            show_message(frame, RES_ERROR_FACE_NOT_FOUND, color=RED, position=(50, 50))

        if status == ERROR_EYES_NOT_FOUND:
            show_message(frame, RES_ERROR_EYES_NOT_FOUND, color=RED, position=(50, 50))

        if status == ERROR_EYES_FAR_AWAY:
            show_message(frame, RES_ERROR_EYES_FAR_AWAY, color=ORANGE, position=(50, 50))

        # If success proceed with flow
        if status == SUCCESS and eye_original is not None:

            # Listen for 'R' key events to register a user
            if cv.waitKey(10) == KEY_CODE_R:
                is_eye_ok_to_use = input("Is Eye Frame OK to be used? (y/n):")

                if is_eye_ok_to_use == 'y':
                    user_code = input("Enter a unique code/name this user:")
                    register(eye_original, user_code)

            # If no registration occurred, validate the frame
            else:
                is_valid, person = validate(frame, eye_original)

                if is_valid:
                    show_message(frame, RES_SUBJ_REC + ': ' + person, color=GREEN, position=(50, 50))
                else:
                    show_message(frame, RES_SUBJ_NOT_REC, color=RED, position=(50, 50))

        cv.imshow(WINDOW_TITLE_CAM, frame)

        if cv.waitKey(10) == KEY_CODE_ESCAPE:
            break

    cv.destroyAllWindows()


main()
