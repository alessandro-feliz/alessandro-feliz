##############
# Trabalho Prático MOT
##############

clear all;  % Clear the workspace
close all;  % Close all figures

HealthyKidneyFolder = "Rim_N";
NonHealthyKidneyFolder = "Rim_C";
ComponentsFolder = "Componentes";

%%
% Converts the given file to a list of wavelengths and values
%%
function [x_list, y_list] = ParseFile(file_path, sep)
  % Initialize lists to store x and y
  x_list = [];
  y_list = [];

  % Open the file for reading
  fileID = fopen(file_path, 'r');

  % Check if file opening was successful
  if fileID == -1
    error('Cannot open file for reading');
  end

  % Read the file line by line
  line = fgetl(fileID);
  while ischar(line)
    % Trim the line
    line = strtrim(line);

    % Split the line by comma
    parts = strsplit(line, sep);

    % Convert parts to numeric values
    x = str2double(parts{1});
    y = str2double(parts{2});

    % Add x and y to the lists
    x_list = [x_list, x];
    y_list = [y_list, y];

    % Read the next line
    line = fgetl(fileID);
  end

  % Close the file
  fclose(fileID);
end

%%
% Calculate the absorbances with the given reflectance, transmittance and tissue depth
%%
function [wavelengths, absorbances] = CalculateAbsorbances(wavelengths, reflectances, transmittances, depth)
  % Initialize lists to store abs
  absorbances = [];

  % Iterate through the wavelengths
  for i = 1:length(wavelengths)
    wavelength = wavelengths(i);
    transmittance = 100 - transmittances(i);
    reflectance = 100 - reflectances(i);

    % Calculate absorbance
    absorbance = abs((100 - (transmittance + reflectance)) / depth);

    % Add the absorbance the list
    absorbances = [absorbances, absorbance];
  end
end

%%
% Parse the components
%%
function [components_list, components_values_list] = ParseComponents(folder_path)
  NR_COMPONENTS = 8;

  % Initialize lists to store out values
  components_list = [];
  components_values_list = {};

  files = dir (fullfile(folder_path, '\*.ttt'));
  nr_of_components = length (files);

  for index=1:nr_of_components
    file = fullfile(folder_path, files(index).name);
    [wavelengths absorbances] = ParseFile(file, ' ');

    %min_value = min(absorbances);
    %absorbances = absorbances - min_value;
    norm_absorbances = absorbances;

    figure;
    plot(wavelengths, norm_absorbances, 'b');
    title('Melanina');
    xlabel('\lambda (nm)')
    ylabel('\mu (cm^{-1})')

    % Add to return list
    components_list = [components_list, files(index).name];
    components_values_list{end+1} = norm_absorbances;
  end
end

%%
% Controller to calculate and generate the plot with the absorbances
%%
function [wavelengths, absorbances_list] = ParseAbsorvance(baseFolder, color)
  NR_OF_SAMPLES = 10;
  SAMPLE_DEPTH = 0.05; # mm
  FILE_FORMAT = ".ttt";

  absorbances_list = {};

  hold on;

  % Iterate through each sample file
  for index = 1:NR_OF_SAMPLES
    % Build the path for the reflectance file
    reflectance_path = strcat(baseFolder, "\\Rt", int2str(index), FILE_FORMAT);
    % Parse the file to a list of wavelengths and reflectances
    [wavelengths reflectances] = ParseFile(reflectance_path, ',');

    % Build the path for the transmitance file
    transmitance_path = strcat(baseFolder, "\\Tt", int2str(index), FILE_FORMAT);
    % Parse the file to a list of wavelengths and transmitances
    [wavelengths transmitances] = ParseFile(transmitance_path, ',');

    % Calculate the absorbances
    [wavelengths, absorbances] = CalculateAbsorbances(wavelengths, reflectances, transmitances, SAMPLE_DEPTH);

    % Add to return list
    absorbances_list{end+1} = absorbances;

    % Plot the graph
    plot(wavelengths, absorbances, color);
  end

  title('Absorbance Spectra of Samples');
  xlabel('\lambda (nm)');
  ylabel('\mu (mm^{-1})');
end

%%
% Controller to calculate and generate the plot with the absorbances
%%
function [melanina_weights, lipofuscina_weights] = CreatePigmentBaseline(wavelengths, melanina_spectrum, lipofuscina_spectrum)

  melanina_weights = 3;
  lipofuscina_weights = 5;

  baseline = melanina_weights .* melanina_spectrum + lipofuscina_weights .* lipofuscina_spectrum;

  figure;
  plot(wavelengths, baseline, 'b');
end

function [average_spectrum] = CalculateAverage(spectrums)

  % Initialize an array to store the sum of all spectra
  sum_spectrum = zeros(size(spectrums{1}));

  % Compute the sum of all spectra
  for i = 1:length(spectrums)
    sum_spectrum = sum_spectrum + spectrums{i};
  end

  % Calculate the average spectrum
  average_spectrum = sum_spectrum / length(spectrums);
end

%%
% Main
%%
%[wavelengths healthy_absorbances_list] = ParseAbsorvance(HealthyKidneyFolder, 'g');
%[wavelengths non_healthy_absorbances_list] = ParseAbsorvance(NonHealthyKidneyFolder, 'r');
[components_list components_values_list] = ParseComponents(ComponentsFolder);

%healthy_absorbances_avg = CalculateAverage(healthy_absorbances_list);
%figure;
%plot(wavelengths, healthy_absorbances_avg, 'b');
%title('Avg Healthy');
%figure;
%min_value = min(healthy_absorbances_avg);
%healthy_absorbances_avg = healthy_absorbances_avg - min_value;
%plot(wavelengths, healthy_absorbances_avg, 'b');
%title('Norm Healthy');

%non_healthy_absorbances_avg = CalculateAverage(non_healthy_absorbances_list);
%figure;
%plot(wavelengths, non_healthy_absorbances_avg, 'b');
%title('Avg Non Healthy');
%figure;
%min_value = min(non_healthy_absorbances_avg);
%non_healthy_absorbances_avg = non_healthy_absorbances_avg - min_value;
%plot(wavelengths, non_healthy_absorbances_avg ./ max(non_healthy_absorbances_avg), 'b');
%plot(wavelengths, non_healthy_absorbances_avg, 'b');
%title('Norm Non Healthy');

%melanina_index = 7;
%melanina_spectrum = components_values_list{melanina_index};
%figure;
%plot(wavelengths, melanina_spectrum, 'b');
%title('Melanina');

%lipofuscina_index = 6;
%lipofuscina_spectrum = components_values_list{lipofuscina_index};
%figure;
%plot(wavelengths, lipofuscina_spectrum, 'b');
%title('Liposs');

%csvwrite("wavelengths.txt",wavelengths);
%csvwrite("healthy_absorbance.txt",healthy_absorbances_avg);
%csvwrite("non_healthy_absorbance.txt",non_healthy_absorbances_avg);
%csvwrite("healthy_absorbances.txt",healthy_absorbances_avg ./ max(healthy_absorbances_avg));


