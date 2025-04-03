##################################################################################################
# Language Model using a Neural Network to predict words based on previous words (context)
##################################################################################################

# Based (with some adaptations) on the work of:
# https://medium.com/analytics-vidhya/build-a-simple-predictive-keyboard-using-python-and-keras-b78d3c88cffb

# required imports
import os
import re
import warnings
import numpy as np
import pickle
import heapq
import time

from nltk import ngrams
from nltk.tokenize import RegexpTokenizer
from sklearn.model_selection import train_test_split
from tensorflow.keras.optimizers import RMSprop
from tensorflow.keras.utils import to_categorical
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Embedding, GRU, Dense
from keras.models import Sequential, load_model
from keras.layers.core import Dense, Activation
from keras.layers import LSTM

##########################################
# Variables
##########################################

# Folders

# Report folder to use
FOLDER_REPORTS = 'Reports/'
# Model folder
FOLDER_MODEL = "models"

# Train Reports
FOLDER_REPORTS_TRAIN = 'Reports Evaluation - Train/'
# Eval Reports
FOLDER_REPORTS_EVAL = 'Reports Evaluation - Eval/'

# Files
FILE_MODEL = "model.h5"
FILE_HISTORY = "history.dat"
FILE_TOKENS = "tokens.dat"
FILE_TOKENS_INDEX = "tokens_index.dat"

# Modes
# Will only execute the train process
MODE_TRAIN = 1
# Will only execute the predict process
MODE_PREDICT = 2
# Will execute the train and prediction process
MODE_COMPLETE_RUN = 3
# Will only execute the Key Stroke Savings and Prediction Time Evaluation process
MODE_EVALUATION = 4

# Execution mode
MODE_IN_EXECUTION = MODE_PREDICT

# Global
CONTEXT_SIZE = 5
NR_PREDICTIONS = 3

##############
# NN related
##############

# Verbosity mode (0 = silent, 1 = progress bar, 2 = one line per epoch)
VERBOSE = 1
# The number of samples to work through before updating the internal model parameters
BATCH_SIZE = 128
# The number times that the learning algorithm will work through the entire training dataset
EPOCHS = 10
# Fraction of the training data to be used as validation data
VAL_SPLIT = 0.25
# Learning speed
LEARNING_RATE = 0.01
# Hidden nodes
HIDDEN_CELLS = 256


##########################################
# Entry point
##########################################

def main():

    if MODE_IN_EXECUTION == MODE_TRAIN:
        train(FOLDER_REPORTS)

    if MODE_IN_EXECUTION == MODE_PREDICT:
        predict("presença de escassos divertículos na dependência da")

    if MODE_IN_EXECUTION == MODE_COMPLETE_RUN:
        train(FOLDER_REPORTS)
        predict("presença de escassos divertículos na dependência da")

    if MODE_IN_EXECUTION == MODE_EVALUATION:
        evaluate()


##########################################
# Train Process
##########################################

# Controls/orchestrates the training process.
def train(folder_reports):
    # Get all reports from the report folder in a single string
    text = read_reports(folder_reports)

    ####################################################################################################
    # Reports files were already pre-processed. If they needed to be pre-process we would do it here.
    ####################################################################################################

    # Split the string in a token array (with words and symbols)
    tokens = tokenize(text)

    # Get a list of tuples with unique tokens and their respective index (to be used as a numeric ID)
    unique_tokens_index, unique_tokens = get_unique_tokens(tokens)

    # Create a list of tuples with the predicted token and respective context tokens
    predicted_token, context_tokens = create_ngrams(tokens)

    # Create a list of tuples with the predicted token and respective context tokens in a format to be feed to the NN
    context_words_feats, predicted_token_label = create_features(unique_tokens_index, unique_tokens, context_tokens, predicted_token)

    # Create a NN model based on LSTM
    model = build_model(unique_tokens)

    # Train the NN model
    history = train_model(model, context_words_feats, predicted_token_label)

    # Saves everything needed for prediction
    save_model(model, history, unique_tokens_index, unique_tokens)


# Reads all report in the given folder and returns their content in a single string.
def read_reports(folder):
    print(" Reading reports ")

    # Initialize full text
    full_text = ''

    # List reports from report folder
    for file in os.listdir(folder):
        # Create file buffer
        file_buffer = open(os.path.join(folder, file), mode='r', encoding='utf-8')

        # Read content from file buffer
        text = file_buffer.read()

        # Validate if connector is needed (space between texts)
        connector = ''
        if not full_text:
            connector = ' '

        # Concat text to full text
        full_text = full_text + connector + text

        # Close file buffer
        file_buffer.close()

    return full_text


# Splits the given text in a token array (with words and symbols).
def tokenize(text):
    print(" Tokenize ")
    tokenizer = RegexpTokenizer(r'\w+')
    tokens = tokenizer.tokenize(text)
    return tokens


# Returns a list of tuples of unique tokens and their indexes to be used as a numeric ID.
def get_unique_tokens(tokens):
    print(" Get unique tokens ")

    # Get unique tokens
    unique_tokens = np.unique(tokens)
    # Get unique tokens index
    unique_tokens_index = dict((c, i) for i, c in enumerate(unique_tokens))

    return unique_tokens_index, unique_tokens


# Create a list of tuples with the predicted word and respective context words.
def create_ngrams(tokens):
    print(" Create N-Grams ")

    predicted_token = []
    context_tokens = []

    # Go through the tokens and create the tuple - context words + predicted word
    for i in range(len(tokens) - CONTEXT_SIZE):
        context_tokens.append(tokens[i:i + CONTEXT_SIZE])
        predicted_token.append(tokens[i + CONTEXT_SIZE])

    return predicted_token, context_tokens


# Create a list of tuples with the predicted word and respective context words in a format to be feed to the NN.
def create_features(unique_tokens_index, unique_tokens, context_tokens, predicted_token):
    print(" Create Features ")

    context_tokens_features = np.zeros((len(context_tokens), CONTEXT_SIZE, len(unique_tokens)), dtype=bool)
    predicted_token_label = np.zeros((len(predicted_token), len(unique_tokens)), dtype=bool)

    for i, each_words in enumerate(context_tokens):
        for j, each_word in enumerate(each_words):
            context_tokens_features[i, j, unique_tokens_index[each_word]] = 1
        predicted_token_label[i, unique_tokens_index[predicted_token[i]]] = 1

    return context_tokens_features, predicted_token_label


# Create a NN model based on LSTM.
def build_model(unique_words):
    print(" Create and build the NN model ")

    # LSTM documentation:
    # https://keras.io/api/layers/recurrent_layers/lstm/
    # https://machinelearningmastery.com/5-step-life-cycle-long-short-term-memory-models-keras/
    model = Sequential()

    # Single-layer LSTM model with HIDDEN_CELLS neurons
    # model.add(LSTM(HIDDEN_CELLS, input_shape=(CONTEXT_SIZE, len(unique_words))))

    # Two-layer LSTM model with HIDDEN_CELLS neuron
    model.add(LSTM(HIDDEN_CELLS, return_sequences=True, input_shape=(CONTEXT_SIZE, len(unique_words))))
    model.add(LSTM(HIDDEN_CELLS))

    # Output layer
    model.add(Dense(len(unique_words)))
    # A softmax function for activation
    model.add(Activation('softmax'))

    return model


# Train the NN model.
def train_model(model, context_words_feats, predicted_token_label):
    print(" Train the NN model ")

    optimizer = RMSprop(learning_rate=LEARNING_RATE)

    model.compile(loss='categorical_crossentropy', optimizer=optimizer, metrics=['accuracy'])

    # Fit documentation:
    # https://keras.rstudio.com/reference/fit.html
    history = model.fit(context_words_feats, predicted_token_label,
                        validation_split=VAL_SPLIT,
                        batch_size=BATCH_SIZE,
                        epochs=EPOCHS,
                        verbose=VERBOSE,
                        shuffle=False).history

    return history


# Saves everything needed for prediction.
def save_model(model, history, unique_tokens_index, unique_tokens):
    print(" Saving the NN model ")

    # Clear model folder
    clear_model_folder()

    # Save model
    model_file = os.path.join(FOLDER_MODEL, FILE_MODEL)
    model.save(model_file)

    # Save history
    history_file = os.path.join(FOLDER_MODEL, FILE_HISTORY)
    pickle.dump(history, open(history_file, "wb"))

    # Save tokens
    token_file = os.path.join(FOLDER_MODEL, FILE_TOKENS)
    pickle.dump(unique_tokens, open(token_file, "wb"))

    # Save tokens index
    token_index_file = os.path.join(FOLDER_MODEL, FILE_TOKENS_INDEX)
    pickle.dump(unique_tokens_index, open(token_index_file, "wb"))


# Deletes all files in the model folder
def clear_model_folder():
    # Go through each file in FOLDER_MODEL
    for file in os.listdir(FOLDER_MODEL):
        file_path = os.path.join(FOLDER_MODEL, file)
        # Delete file
        os.unlink(file_path)


##########################################
# Predict Process
##########################################

# Controls/orchestrates the prediction process.
def predict(text):
    # Loads everything needed for prediction
    model, history, unique_tokens_index, unique_tokens = open_model()

    # Split the string in a token array (with words and symbols)
    sample_tokens = tokenize(text)

    # Select the first CONTEXT_SIZE tokens
    context_tokens = sample_tokens[0:CONTEXT_SIZE]

    # Predict
    predictions = predict_completions(model, unique_tokens_index, unique_tokens, context_tokens, NR_PREDICTIONS)

    print("Full text: " + text)
    print("Partial text: " + " ".join(context_tokens))
    print("Predictions: ", predictions)


# Loads everything needed for prediction.
def open_model():
    print(" Loading the NN model ")

    # Load model
    model_file = os.path.join(FOLDER_MODEL, FILE_MODEL)
    model = load_model(model_file)

    # Load history
    history_file = os.path.join(FOLDER_MODEL, FILE_HISTORY)
    history = pickle.load(open(history_file, "rb"))

    # Load tokens index
    unique_tokens_index_file = os.path.join(FOLDER_MODEL, FILE_TOKENS_INDEX)
    unique_tokens_index = pickle.load(open(unique_tokens_index_file, "rb"))

    # Load tokens
    unique_tokens_file = os.path.join(FOLDER_MODEL, FILE_TOKENS)
    unique_tokens = pickle.load(open(unique_tokens_file, "rb"))

    return model, history, unique_tokens_index, unique_tokens


# Predict
def predict_completions(model, unique_tokens_index, unique_tokens, context_tokens, nr_predictions):

    # Transform the given context tokens into features that will be feed in the NN model for prediction
    context_features = prepare_context_features(context_tokens, unique_tokens_index, unique_tokens)

    # Predict
    predictions = model.predict(context_features, verbose=VERBOSE)[0]

    # Get best prediction
    best_predictions_index = sample(predictions, nr_predictions)

    # Return tokens based on the predictions index
    return [unique_tokens[index] for index in best_predictions_index]


# Transform the given context tokens into features that will be feed in the NN model for prediction.
def prepare_context_features(context_tokens, unique_tokens_index, unique_tokens):
    context_features = np.zeros((1, CONTEXT_SIZE, len(unique_tokens)))

    for i, token in enumerate(context_tokens):
        if token in unique_tokens_index:
            context_features[0, i, unique_tokens_index[token]] = 1

    return context_features


# Get best prediction
def sample(predictions, nr_predictions):
    predictions = np.asarray(predictions).astype('float64')
    predictions = np.log(predictions)
    exp_predictions = np.exp(predictions)
    predictions = exp_predictions / np.sum(exp_predictions)

    return heapq.nlargest(nr_predictions, range(len(predictions)), predictions.take)


##########################################
# Key Stroke Savings and Prediction Time Evaluation
##########################################

# Controls/orchestrates the Key Stroke Savings and Prediction Time evaluation.
def evaluate():

    # Do the model training if needed
    # train(FOLDER_REPORTS_TRAIN)

    # Variables for evaluation
    total_keystrokes = 0
    keystrokes = 0
    nr_of_predictions = 0
    total_time_prediction = 0

    # Loads everything needed for prediction
    model, history, unique_tokens_index, unique_tokens = open_model()

    # Read eval reports
    for file in os.listdir(FOLDER_REPORTS_EVAL):
        # Create file buffer
        file_buffer = open(os.path.join(FOLDER_REPORTS_EVAL, file), mode='r', encoding='utf-8')

        # Read content from file buffer
        text = file_buffer.read()

        text_words = tokenize(text)
        nr_text_words = len(text_words)

        if nr_text_words > 0:

            total_keystrokes = total_keystrokes + len(text)

            for i in range(nr_text_words - CONTEXT_SIZE):

                # Grab context
                context = text_words[i:i+CONTEXT_SIZE]
                next_word = text_words[i+CONTEXT_SIZE]

                # Predict
                prediction_start_time = time.time()
                predictions = predict_completions(model, unique_tokens_index, unique_tokens, context, NR_PREDICTIONS)
                prediction_end_time = time.time()
                prediction_duration = prediction_end_time - prediction_start_time

                nr_of_predictions = nr_of_predictions + 1
                total_time_prediction = total_time_prediction + prediction_duration

                # Validate if prediction was successful
                prediction_success = False
                for prediction in predictions:
                    if prediction == next_word:
                        prediction_success = True
                        break

                if prediction_success:
                    continue
                else:
                    keystrokes = keystrokes + len(next_word)

    print("Total number of predictions: " + str(nr_of_predictions))
    print("Time per prediction: " + str(total_time_prediction / nr_of_predictions))
    print("Total number of keystrokes: " + str(total_keystrokes))
    print("Number of keystrokes: " + str(keystrokes))
    print("Keystroke Savings: " + str(keystrokes / total_keystrokes))


# Entry method
main()
